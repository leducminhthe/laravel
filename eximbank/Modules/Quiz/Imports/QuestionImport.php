<?php
namespace Modules\Quiz\Imports;

use Modules\Quiz\Entities\Question;
use Modules\Quiz\Entities\QuestionAnswer;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionImport implements ToModel, WithStartRow
{
    public $errors;
    protected $plat = 0;
    public $arr_question_multiple;

    public function __construct($category_id)
    {
        $this->errors = [];
        $this->category_id = $category_id;
        $this->arr_question_multiple = [];
    }

    public function model(array $row)
    {
        $error = false;
        $index = (int) $row[0]; //STT
        $name_or_title = $row[1]; //Tên câu hỏi hoặc đáp án
        $type_or_group = $row[2]; //Loại câu hỏi hoặc Nhóm đáp án
        $choice_or_answer = $row[3]; //Loại trắc nghiệm hoặc đáp án câu hỏi
        $multiple_full_score = $row[4] ?? 0; //Tính điểm trắc nghiệm chọn nhiều
        $answer_horizontal = $row[5] ?? 0; //Xếp ngang đáp án
        $shuffle_answers = $row[6] ?? 0; //Xáo trộn đáp án
        $feedback_answer = $row[7] ?? null; //Phản hồi đáp án
        $note_question = $row[8] ?? null; //Chú thích câu hỏi

        if($index){
            $this->plat = 0;

            if(!isset($row[2])){
                $this->errors[] = 'Câu hỏi số <b>'. $index .'</b> chưa chọn loại';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        $type_question = [
            1 => 'multiple-choise',
            2 => 'essay',
            3 => 'matching',
            4 => 'fill_in',
            5 => 'fill_in_correct',
            6 => 'select_word_correct',
        ];

        if($index){
            $question = new Question();
            $question->category_id = $this->category_id;
            $question->name = $name_or_title;
            $question->type = $type_question[$type_or_group];
            $question->multiple = ($type_or_group == 1) ? ((int)$choice_or_answer ?? 1 ?? 0) : 0;
            $question->multiple_full_score = ($type_or_group == 1) ? ($multiple_full_score) : 0;
            $question->answer_horizontal = ($type_or_group == 1) ? ($answer_horizontal) : 0;
            $question->note = $note_question;
            $question->status = 2;
            $question->feedback = '';
            $question->created_by = profile()->user_id;
            $question->updated_by = profile()->user_id;
            $question->shuffle_answers = $shuffle_answers;
            $question->save();
        }else{
            if ($name_or_title && $this->plat == 0) {
                $question = Question::where('category_id', $this->category_id)->orderBy('id', 'DESC')->first();

                $answer = new QuestionAnswer();
                $answer->question_id = $question->id;
                $answer->title = $name_or_title;
                $answer->correct_answer = (in_array($question->type, ['multiple-choise', 'select_word_correct']) && $question->multiple == 0) ? ((int)$choice_or_answer ?? 1 ?? 0): 0;
                $answer->percent_answer = ($question->type == 'multiple-choise' && $question->multiple == 1) ? $choice_or_answer : 0;
                $answer->feedback_answer = $feedback_answer;
                $answer->matching_answer = ($question->type == 'matching') ? $choice_or_answer : null;
                $answer->fill_in_correct_answer = ($question->type == 'fill_in_correct') ? $choice_or_answer : null;;
                $answer->select_word_correct = $type_or_group;
                $answer->save();

                if(($question->type == 'multiple-choise' && $question->multiple == 1)){
                    $percent_answer = QuestionAnswer::where('question_id', $question->id)->where('percent_answer', 1)->count();
                    if($percent_answer > 0){
                        $this->arr_question_multiple[$question->id] = (1/$percent_answer)*100;
                    }
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
