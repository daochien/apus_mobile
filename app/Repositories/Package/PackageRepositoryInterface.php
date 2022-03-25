<?php

namespace App\Repositories\Package;

use App\Repositories\BaseRepositoryInterface;

interface PackageRepositoryInterface extends BaseRepositoryInterface
{
    public function getAll($params = []);
}
