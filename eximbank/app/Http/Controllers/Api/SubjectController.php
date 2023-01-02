<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\SubjectModel;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class SubjectController extends Controller
{
    protected $model = SubjectModel::class;

//    protected function runIndexFetchQuery(Request $request, Builder $query, int $paginationLimit)
//    {
//        $abc = \DB::table('el_subject')->where('id',2)->get();
//         dd($abc);
//        \DB::enableQueryLog();
//          return $query->paginate($paginationLimit);
//        $abc = \DB::getQueryLog($test);
//        dd($abc);
//    }
}
