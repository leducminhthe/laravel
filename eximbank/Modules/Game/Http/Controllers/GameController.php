<?php

namespace Modules\Game\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('game::index');
    }

    public function postPin(Request $request)
    {
        json_result([
            'status' => 'success',
            'message' => 'thành công',
        ]);
    }

    public function identify(Request $request)
    {
        return view('game::index');
    }
    public function wait(Request $request)
    {
        return view('game::index');
    }
    public function start(Request $request)
    {
        json_result([
            'status' => 'success',
            'message' => 'thành công',
        ]);
    }
    public function lobby(Request $request){
        return view('game::index');
    }
    public function startGame(Request $request){
        json_result([
            'status' => 'success',
            'message' => 'thành công',
        ]);
    }
    public function quiz(Request $request){
        return view('game::index');
    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('game::create');
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
        return view('game::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('game::edit');
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
