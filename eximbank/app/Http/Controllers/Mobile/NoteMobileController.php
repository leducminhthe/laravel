<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;

class NoteMobileController extends Controller
{
    public function index(Request $request)
    {
        $lay = 'note';
        $notes = $this->getData($request);
        return view('themes.mobile.frontend.note.index',[
            'lay' => $lay,
            'notes' => $notes,
        ]);
    }

    public function getData(Request $request){
        $type = $request->type;
        $sort = $request->input('sort','id');
        $order = $request->input('order','desc');
        $offset =$request->input('offset',0);
        $limit = $request->input('limit',20);

        $query = Note::query();
        $query->select('el_note.*');
        $query->where('user_id', profile()->user_id);

        $count = $query->count();
        $query->orderBy('el_note.'.$sort,$order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $key => $row) {
            if ($row->date_time == '1970-01-01 08:00:00') {
                $row->date_time = '-';
            }
        }
        return $rows;
//        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveNote(Request $request) {
        $get_name_url = explode('/',url()->previous());
        foreach ($request->contents as $key => $content) {
            $date_times = $request->date_times;
            $model = Note::firstOrNew(['id' => $request->id]);
            $model->date_time = $date_times[$key] ? date("Y-m-d H:i:s", strtotime($date_times[$key])) : null;
            $model->content = $content;
            $model->type = $request->type;
            $model->user_id = profile()->user_id;
            $save = $model->save();
        }
        if ($save) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('themes.mobile.note_mobile.index'),
            ]);
        }
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $new = Note::find($id);
            $new->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    // ĐÓNG GHI CHÚ
    public function closeNote(Request $request){
        $model = Note::find($request->id);
        $model->type = 1;
        $model->save();
    }

    public function edit(Request $request) {
        $note = Note::find($request->id);
        if($note->date_time) {
            $note->datetime = date('Y-m-d\TH:i', strtotime($note->date_time));
        }
        json_result([
            'note' => $note,
        ]);
    }
}
