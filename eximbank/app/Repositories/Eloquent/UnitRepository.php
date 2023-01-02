<?php

namespace App\Repositories\Eloquent;

use App\Models\Categories\Unit;
use App\Repositories\Contracts\UnitRepositoryInterface;

class UnitRepository extends AbstractRepository implements UnitRepositoryInterface
{
    public function __construct(Unit $model)
    {
        parent::__construct($model);
    }
}
