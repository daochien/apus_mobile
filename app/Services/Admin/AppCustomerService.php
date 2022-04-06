<?php

namespace App\Services\Admin;

use App\Models\AppCustomer;
use App\Models\Package;
use App\Models\SourceConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AppCustomerService
{
    public function __construct()
    {
    }

    public function store($attributes, &$msg = '')
    {
        DB::beginTransaction();
        try {

            $package = Package::query()->findOrFail($attributes['package_id']);
            $package->load('source');
            $configs = $this->_transformDataConfigs($attributes['configs']);

            $appCus = AppCustomer::query()->create([
                'customer_name' => $attributes['customer_name'],
                'customer_email' => $attributes['customer_email'],
                'customer_phone' => $attributes['customer_phone'],
                'package_id' => $package->id,
                'status' => $attributes['status'],
                'expired' => Carbon::parse($attributes['expired'])->toDateTimeString(),
                'configs' => json_encode($configs)
            ]);

            DB::commit();

            $this->_cloneSource($appCus->code, $package->source->code);

            return $appCus;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }

    protected function _transformDataConfigs($configs)
    {
        $data = [];

        foreach ($configs as $i => $config) {
            $item = [];
            $item['key'] = $config['key'];
            $item['type'] = $config['type'];
            $item['is_edit'] = $config['is_edit'];
            $item['value'] = $config['new_value'];
            $item['is_group'] = isset($config['is_group']) ? $config['is_group']: 0;
            if (isset($config['is_group']) && $config['is_group'] == SourceConfig::IS_GROUP) {
                foreach ($config['items'] as $child) {
                    $item['items'][] = [
                        'key' => $child['key'],
                        'type' => $child['type'],
                        'is_edit' => $child['is_edit'],
                        'value' => $child['new_value']
                    ];
                }

            }
            $data[] = $item;
        }

        return $data;
    }

    protected function _cloneSource($appCode, $sourceCode)
    {
        $rootPath = 'sources/'.$appCode;
        $copyPath = $sourceCode.'/';
        Storage::copy($rootPath, $copyPath);
    }
}
