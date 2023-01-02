<?php
namespace App\Repositories\District;

use App\Repositories\RepositoryInterface;

interface DistrictRepositoryInterface extends RepositoryInterface
{
    //ví dụ: lấy 5 sản phầm đầu tiên
    public function getDistrict();
    public function getData($request);
}