<?php

namespace App\Repositories\Source;

use App\Repositories\BaseRepositoryInterface;

interface SourceRepositoryInterface extends BaseRepositoryInterface
{
    public function getAll($params = []);
}
