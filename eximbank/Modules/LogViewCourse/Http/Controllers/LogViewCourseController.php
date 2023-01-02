<?php

namespace Modules\LogViewCourse\Http\Controllers;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\LogViewCourse\Entities\LogViewCourse;

class LogViewCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $sort = $request->input('sort','id');
            $order = $request->input('order','desc');
            $offset = $request->input('offset',0);
            $limit = $request->input('limit',20);
            $user = $request->input('user',0);
            $course = $request->input('course','');
            $query = LogViewCourse::query();
            $query->select('*')->get();
            $query->where('user_id', '>', 2);
            if ($course){
                $query->where(function (Builder $subquery) use($course){
                   $subquery->orWhere('course_code','=',$course);
                   $subquery->orWhere('course_name','like',"'%".$course."%'");
                });
            }
            if ($fromDate && $toDate){
                $query->where(function(Builder $sub_query) use ($fromDate, $toDate){
                    $sub_query->orWhere('last_access','>=',$fromDate);
                    $sub_query->orWhere('last_access','<=', $toDate);
                });
            }
            if ($user){
                $query->where('user_id',$user);
            }
            $count = $query ->count();
            $query -> orderBy( $sort,$order);
            $query ->offset($offset);
            $query ->limit($limit);
            $rows = $query ->get();
            foreach ($rows as $row) {
                $profile = Profile::find($row->user_id);
                $row->user_code = @$profile->code;
                
                $row->start_date = get_datetime($row->created_at);
                $row->end_date = get_datetime($row->updated_at);
                
            }

            json_result(['total' => $count, 'rows' => $rows]);
        }

        // return view('logviewcourse::backend.index');
        return view('backend.history.index',[
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('logviewcourse::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('logviewcourse::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('logviewcourse::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
