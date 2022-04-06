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

    public function update($id, $attributes, &$msg = '')
    {
        DB::beginTransaction();
        try {

            $package = $this->packageRepo->findById($id);
            if (!$package) {
                throw new \Exception('Không tìm thấy thông tin package');
            }

            $package->name = $attributes['name'];
            $package->desc = $attributes['desc'];
            $package->status = $attributes['status'];
            $package->price = $attributes['price'];
            $package->source_id = $attributes['source_id'];

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
            $dataItem = $this->_formatConfig($conf);
            if (isset($conf['is_group']) && $conf['is_group'] == SourceConfig::IS_GROUP) {
                foreach ($conf['items'] as $item) {
                    $dataItem['items'][] = $this->_formatConfig($item);
                }
            }

            $newConfigs[] = $dataItem;
        }
        return $newConfigs;
    }

    protected function _formatConfig($config)
    {
        $item = [
            'id' => $config['id'],
            'key' => $config['key'],
            'type' => $config['type'],
            'is_edit' => $config['is_edit'],
        ];
        if (isset($config['is_group']) && $config['is_group'] == SourceConfig::IS_GROUP) {
            $item['is_group'] = SourceConfig::IS_GROUP;
        }

        if (in_array($config['type'], [SourceConfig::TYPE_CHECKBOX, SourceConfig::TYPE_RADIO])) {
            $rawValue = !is_array($config['value']) ? json_decode($config['value'], true): $config['value'];
            $oldValue = $rawValue;
            $value = isset($config['new_value']) ? $config['new_value']: $rawValue;
        } else if ($config['type'] == SourceConfig::TYPE_FILE) {
            $oldValue = $config['value'];
            $value = $oldValue;
        } else {
            $oldValue = $config['value'];
            $value = $config['value'];
        }
        $item['old_value'] = $oldValue;
        $item['value'] = $value;
        return $item;
    }
}
