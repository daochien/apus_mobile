<?php

namespace App\Repositories\Package;

use App\Models\Package;
use App\Repositories\BaseRepository;

class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }

    public function getAll($params = [])
    {
        return $this->model->orderBy('id', 'DESC')->paginate(20);
    }
}
