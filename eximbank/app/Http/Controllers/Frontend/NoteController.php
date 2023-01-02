<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Contact;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function index() {
        $notes = Note::get();
        return view('frontend.note', ['notes' => $notes]);
    }
    public function getData(Request $request){
        $type = $request -> type;
        $sort = $request ->input('sort','id');
        $order = $request ->input('order','desc');
        $offset =$request ->input('offset',0);
        $limit = $request ->input('limit',20);
        $query = Note::query();
        $query->select('el_note.*');
        $query->where('user_id',profile()->user_id);

        $count = $query ->count();
        $query -> orderBy('el_note.'.$sort,$order);
        $query ->offset($offset);
        $query->limit($limit);
        $rows = $query ->get();
        foreach ($rows as $key => $row) {
            if ($row->date_time == '1970-01-01 08:00:00') {
                $row->date_time = '-';
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveNote(Request $request) {
        $date_times = $request->date_times;

        foreach ($request->contents as $key => $content) {
            $model = new Note();
            $model->date_time = ($date_times[$key] ? date("Y-m-d H:i:s", strtotime($date_times[$key])) : date('Y-m-d H:i:s'));
            $model->content = $content;
            $model->type = $request->type;
            $model->user_id = profile()->user_id;
            $save = $model->save();
        }

        if ($save) {
            return redirect()->back();
        }
    }

    public function closeNote(Request $request) {
        $model = Note::find($request->id);
        $model->type = 1;
        $model->save();
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
}
