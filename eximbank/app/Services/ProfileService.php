<?php

namespace App\Services;

use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Repositories\Eloquent\ProfileRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Service Layer - Get a listing of the resource.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        try {
            $query = QueryBuilder::for(Profile::class)
                ->allowedFields('id','code','name','firstname','lastname')
                ->allowedFilters(['code','name'])
                ->allowedFilters([
                    AllowedFilter::exact('code')
                ])
                ->defaultSort('-name')
                ->allowedSorts('name', 'code')
                ->jsonPaginate();
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
