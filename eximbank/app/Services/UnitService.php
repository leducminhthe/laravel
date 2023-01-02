<?php

namespace App\Services;

use App\Models\Categories\Unit;
use App\Repositories\Contracts\UnitRepositoryInterface;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UnitService
{
    protected $unitRepository;

    public function __construct(UnitRepositoryInterface $unitRepository)
    {
        $this->unitRepository = $unitRepository;
    }

    /**
     * Service Layer - Get a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        try {
//            \DB::enableQueryLog();
            $fields = ['id','code','name'];
            $query = QueryBuilder::for(Unit::class)
                ->select($fields)
                ->allowedFields($fields)
                ->allowedFilters(['code','name'])
                ->allowedFilters([
                    AllowedFilter::exact('code')
                ])
                ->defaultSort('-name')
                ->allowedSorts('name', 'code')
                ->jsonPaginate();
//                    $query->get();
//            $abc = \DB::getQueryLog();
//            dd($abc);
//                ->paginate()->appends(request()->query());
            return $query;
        } catch (Exception $e) {
            dd($e->getMessage());
        }

    }

    /**
     * Service Layer - Store a newly created resource in storage.
     *
     * @return \Illuminate\Support\Collection
     */
    public function store($request)
    {
        try {
            return $this->unitRepository->create($request);
        } catch (\Illuminate\Database\QueryException $e) {
            abort(442,$e->getMessage());
        }

    }

    /**
     * Service Layer - Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function show($id)
    {
            return $this->unitRepository->find($id);


    }

    /**
     * Service Layer - Update the specified resource in storage.
     *
     * @param  array  $request
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function update($request, $id)
    {
        return $this->unitRepository->update($request, $id);
    }

    /**
     * Service Layer - Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function delete($id)
    {
        try {
            return $this->unitRepository->delete($id);
        } catch (\Illuminate\Database\QueryException $e) {
            abort(422,$e->getMessage());
        }
    }
}
