<?php

namespace App\Repositories\Source;

use App\Models\Source;
use App\Repositories\BaseRepository;

class SourceRepository extends BaseRepository implements PackageRepositoryInterface
{
    public function __construct(Source $model)
    {
        parent::__construct($model);
    }

    public function getAll($params = [])
    {
        return $this->model->orderBy('id', 'DESC')->paginate(20);
    }
}
