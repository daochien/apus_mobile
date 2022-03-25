<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Models\Package;
use App\Models\Source;
use App\Repositories\Package\PackageRepositoryInterface;
use Illuminate\Support\Facades\DB;
use ZanySoft\Zip\Zip;

class PackageService
{

    protected $packageRepo;

    protected $fileHelper;

    public function __construct(
        PackageRepositoryInterface $packageRepo,
        FileHelper                 $fileHelper
    )
    {
        $this->packageRepo = $packageRepo;
        $this->fileHelper = $fileHelper;
    }

    public function repo()
    {
        return $this->packageRepo;
    }

    public function fetchList($params = [])
    {
        return $this->packageRepo->getAll();
    }

    public function store($attributes, &$msg = '')
    {
        DB::beginTransaction();
        try {

            $source = $this->packageRepo->store($attributes);

            if (!empty($attributes['avatar'])) {
                $file = $attributes['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), Package::DIR_UPLOAD);

                if (!$image) {
                    throw new \Exception('Upload ảnh đại diện thất bại');
                }

                $source->avatar = $image;
            }

            DB::commit();
            return $source->save();

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }
}
