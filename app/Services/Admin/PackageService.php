<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Models\Package;
use App\Models\Source;
use App\Models\SourceConfig;
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

            $package = $this->packageRepo->store($attributes);

            if (!empty($attributes['avatar'])) {
                $file = $attributes['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), Package::DIR_UPLOAD);

                if (!$image) {
                    throw new \Exception('Upload ảnh đại diện thất bại');
                }

                $package->avatar = $image;
            }

            if (!empty($attributes['configs'])) {
                $configs = $this->_transformDataConfigs($attributes['configs']);
                $package->configs = json_encode($configs);
            }

            DB::commit();
            return $package->save();

        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }

    protected function _transformDataConfigs($configs)
    {
        $newConfigs = [];
        foreach ($configs as $conf) {
            if (in_array($conf['type'], [SourceConfig::TYPE_CHECKBOX, SourceConfig::TYPE_RADIO])) {
                $oldValue = json_decode($conf['value'], true);
            } else {
                $oldValue = $conf['value'];
            }
            $newConfigs[] = [
                'id' => $conf['id'],
                'key' => $conf['key'],
                'type' => $conf['type'],
                'value' => $conf['new_value'],
                'old_value' => $oldValue,
                'is_edit' => $conf['is_edit'],
            ];
        }

        return $newConfigs;
    }
}
