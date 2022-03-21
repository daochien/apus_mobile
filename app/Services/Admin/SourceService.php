<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Repositories\Source\SourceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use ZanySoft\Zip\Zip;

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
        DB::beginTransaction();
        try {

            $source = $this->sourceRepo->store($attributes);

            if (!empty($attributes['avatar'])) {
                $file = $attributes['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), '/images/');

                if (!$image) {
                    return false;
                }

                $source->avatar = $image;
            }

            if (!empty($attributes['source'])) {
                $file = $attributes['source'];
                $fileName = $source->code.'.'.$file->getClientOriginalExtension();
                $path = $this->fileHelper->saveFile($file, $fileName, '/sources/');

                if (!$path) {
                    return false;
                }

                $source->path = $path;
                $location = storage_path('app/public/sources/'.$fileName);
                $zip = Zip::open($location);
                $zip->extract(storage_path('app/public/sources/'.$source->code));
            }

            DB::commit();
            return $source->save();

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            dd($e);
            return false;
        }
    }

}
