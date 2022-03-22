<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Models\SourceConfig;
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
            $this->_storeConfigs($source->id, $attributes['configs']);
            if (!empty($attributes['avatar'])) {
                $file = $attributes['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), '/images/');

                if (!$image) {
                    throw new \Exception('Upload ảnh đại diện thất bại');
                }

                $source->avatar = $image;
            }

            if (!empty($attributes['source'])) {
                $file = $attributes['source'];
                $fileName = $source->code.'.'.$file->getClientOriginalExtension();
                $path = $this->fileHelper->saveFile($file, $fileName, '/sources/');

                if (!$path) {
                    throw new \Exception('Upload file thất bại');
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
            return false;
        }
    }

    protected function _storeConfigs($sourceId, $configs)
    {
        if (empty($configs)) {
            return false;
        }
        $dataInserts = [];
        foreach ($configs as $config) {
            $insert['source_id'] = $sourceId;
            $insert['key'] = $config['key'];
            $insert['type'] = $config['type'];
            $insert['is_edit'] = !empty($config['is_edit']) ?? 0;
            if (in_array($config['type'], ['radio', 'checkbox'])) {
                $insert['value'] = json_encode($config['value']);
            } else {
                $insert['value'] = $config['value'];
            }
            $dataInserts[] = $insert;
        }

        return SourceConfig::query()->insert($dataInserts);
    }

}
