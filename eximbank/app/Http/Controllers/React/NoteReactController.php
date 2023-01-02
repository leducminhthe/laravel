<?php

namespace App\Http\Controllers\React;

use App\Models\Contact;
use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteReactController extends Controller
{
    public function index()
    {
        return view('react.note.index');
    }

    public function getData(Request $request){
        $year = $request->searchYear ?? date('Y');
        
        $query = Note::query();
        $query->select([
            'id',
            'date_time',
            'content'
        ]);
        $query->where('user_id', profile()->user_id);
        $query->whereYear('date_time', '=', $year);

        if ($request->dateSearch) {
            $query->whereDate('date_time', date_convert($request->dateSearch, '00:00:00'));
        }

        $query->orderBy('id','desc');

        $rows = $query->get();
        foreach ($rows as $key => $row) {
            $row->date_time = get_date($row->date_time, 'd/m/Y H:i:s');
        }
        
        return response()->json([
            'rows' => $rows,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('checked', null);

        Note::destroy($ids);

        $data = Note::where('user_id',profile()->user_id)->orderBy('id','desc')->get(['date_time','content']);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'data' => $data
        ]);
    }
}
