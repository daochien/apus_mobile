<?php

namespace App\Services\Admin;

use App\Models\AppCustomer;
use App\Models\Package;
use App\Models\SourceConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppCustomerService
{
    public function __construct()
    {
    }

    public function store($attributes, &$msg = '')
    {
        DB::beginTransaction();
        try {

            $configs = $this->_transformDataConfigs($attributes['configs']);

            $appCus = AppCustomer::query()->create([
                'customer_name' => $attributes['customer_name'],
                'customer_email' => $attributes['customer_email'],
                'customer_phone' => $attributes['customer_phone'],
                'package_id' => $attributes['package_id'],
                'status' => $attributes['status'],
                'expired' => Carbon::parse($attributes['expired'])->toDateTimeString(),
                'configs' => json_encode($configs)
            ]);
            DB::commit();
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
        foreach ($configs as $config) {
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
}
