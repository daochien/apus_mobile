<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Repositories\Source\SourceRepositoryInterface;

class SourceService
{

    protected $sourceRepo;

    protected $fileHelper;

    public function __construct(
        SourceRepositoryInterface $sourceRepo,
        FileHelper $fileHelper
    )
    {
        $this->sourceRepo = $sourceRepo;
        $this->fileHelper = $fileHelper;
    }

    public function repo()
    {
        return $this->sourceRepo;
    }

    public function fetchList($params = [])
    {
        return $this->sourceRepo->getAll();
    }

    public function store($attributes, &$msg = '')
    {
        try {
            if (!empty($attribute['avatar'])) {
                $file = $attribute['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName());

                if (!$image) {
                    return false;
                }

                $attributes['avatar'] = $image;
            }

            if (!empty($attribute['source'])) {
                $file = $attribute['source'];
                $path = $this->fileHelper->saveFile($file, $file->getClientOriginalName());

                if (!$path) {
                    return false;
                }

                $attributes['path'] = $path;
            }

            return $this->sourceRepo->store($attributes);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            return false;
        }

    }
}
