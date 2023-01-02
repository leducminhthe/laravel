<?php

namespace App\Http\Controllers\React;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizNoteByUserSecond;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizType;
use Modules\Quiz\Entities\QuizRegister;

class QuizReactController extends Controller
{
    public function index()
    {
        return view('react.quiz.index');
    }

    public function dataQuizType()
    {
        $quiz_types = QuizType::get();
        return response()->json([
            'quiz_types' => $quiz_types,
        ]);
    }

    public function dataQuiz(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $search = $request->search;
        $start_date = $request->dateFrom;
        $end_date = $request->dateTo;
        $type_id = $request->type;

        $user_id = profile()->user_id;

        $quizIssets = [];
        $quizParts = [];
        $registers = QuizRegister::where('user_id', $user_id)->get(['part_id', 'quiz_id']);
        foreach ($registers as $key => $register) {
            $quizIssets[] = (int) $register->quiz_id;
            $quizParts[] = (int) $register->part_id;
        }

        $quizs = Quiz::where(['quiz_not_register' => 1, 'status' => 1, 'is_open' => 1, 'quiz_type' => 3])
        ->whereNotIn('id', $quizIssets)
        ->get(['id']);
        foreach($quizs as $quiz) {
            $part = QuizPart::where('end_date', '>', date('Y-m-d H:i:s'))->where('quiz_id', $quiz->id)->first(['id']);
            if(isset($part)) {
                array_push($quizParts, $part->id);
            }
        }

        $query = DB::query();
        $query->select([
            'a.id',
            'a.quiz_type',
            'a.name AS quiz_name',
            'a.view_result',
            'a.img',
            'b.id AS part_id',
            'b.start_date AS start_date',
            'b.end_date AS end_date',
        ]);
        $query->from('el_quiz AS a');
        $query->join('el_quiz_part AS b', 'b.quiz_id', '=', 'a.id');
        $query->whereIn('b.id', $quizParts);
        $query->where('a.status', '=', 1);
        $query->where('a.is_open', '=', 1);
        $query->where('a.quiz_type', '=', 3);

        if ($search){
            $query->where('a.name', 'like', '%'.$search.'%');
        }

        if ($start_date) {
            $query->where('b.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('b.start_date', '<=', date_convert($end_date));
        }

        if($type_id) {
            $query->where('a.type_id', '=', $type_id);
        }

        $query->orderBy($sort, $order);
        $rows = $query->get();
        foreach ($rows as $row) {
            $start_date = $row->start_date;
            $end_date = $row->end_date;

            $count_quiz_user = QuizResult::where('quiz_id', $row->id)->where('part_id', $row->part_id)->where('user_id', '>', 2)->whereNull('text_quiz')->count();
            $row->count_quiz_user = $count_quiz_user;

            $row->count_downt = $start_date;

            $date_start_quiz = Carbon::parse($start_date)->format('Y-m-d');
            if ($date_start_quiz == date('Y-m-d')) {
                $row->check_date_do_quiz = 1;
                $row->date_to_quiz = '';
            } else {
                $row->check_date_do_quiz = 0;
                $row->date_to_quiz = calculate_time_span(strtotime(now()), strtotime($start_date));
            }

            if ($end_date && $end_date < date('Y-m-d H:i:s')){
                $row->closed = 1;
            }

            $row->goquiz_url = '';
			if ($start_date <= date('Y-m-d H:i:s')){
                $row->goquiz_url = route('module.quiz.doquiz.index', [
                    'quiz_id' => $row->id,
                    'part_id' => $row->part_id,
                ]);
            }

            $row->start_date = get_date($start_date, 'H:i d/m/Y');
			$row->end_date = get_date($end_date, 'H:i d/m/Y');

            $row->review_link = route('module.quiz.doquiz.index', [$row->id, $row->part_id]);
            $row->time_quiz = 1;

            $status = Quiz::getStatusUser($row->id, $row->part_id);
            if($status == 0) {
                $row->status = '<span class="text-muted ml-1">'. trans("app.not_tested") .'</span>';
            } else if ($status == 1) {
                $row->status = '<span class="text-success ml-1">'. trans("lasurvey.did") .'</span>';
            } else {
                $row->status = '<span class="text-info ml-1">'. trans("app.exam_taking") .'</span>';
            }

            $check_block_quiz = QuizRegister::where('quiz_id', $row->id)->where('part_id', $row->part_id)->where('user_id', $user_id)->where('block_quiz', 1)->exists();
            if($check_block_quiz){
                $row->link = '<button class="btn">Cấm thi</button>';
            }else if ($row->closed == 1) {
                if($row->view_result == 1) {
                    $row->link = '<a href="' . $row->review_link . '" class="btn"> '. trans("app.review") .'</a>';
                } else {
                    $row->link = '<button class="btn">'. trans("app.exams_ended") .'</button>';
                }
            } else {
                if ($status == 1) {
                    $row->link = '<a href="' . $row->review_link . '" class="btn"> '. trans("app.review") .'</a>';
                } else if($row->goquiz_url) {
                    $row->link = '<a href="' . $row->goquiz_url . '" class="btn">'. trans("app.goquiz") .'</a>';
                } else {
                    $row->time_quiz = 0;
                    $row->link = '<button class="btn notify-goquiz">Bài thi chưa tới giờ</button>';
                }
            }

            $row->image = image_quiz($row->img);
            $row->icon_quiz = asset('images/dashboard/online-course.svg');
            $row->iconWatch = asset('images/stopwatch.png');
        }

        return response()->json([
            'quizs' => $rows,
        ]);
    }
}
