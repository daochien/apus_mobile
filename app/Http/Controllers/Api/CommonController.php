<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\AppCustomer;
use App\Models\Source;
use App\Models\SourceConfig;
use App\Models\SourceConfigItem;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getConfig($code)
    {
        $source = Source::query()->where('code', $code)->first();
        if (!$source) {
            return response_error('source not found!');
        }

        $source->load('configs.items');

        $configs = [];
        foreach ($source->configs as $config) {
            if ($config->is_group == SourceConfig::IS_GROUP) {
                $value = [];
                foreach ($config->items as $item) {
                    if ($item->type == SourceConfig::TYPE_FILE) {
                        $value[$item->key] = FileHelper::getLink($item->value, SourceConfig::PATH_GET_FILE);
                    } else if (in_array($item->type, [SourceConfigItem::TYPE_RADIO, SourceConfigItem::TYPE_CHECKBOX])) {
                        $value[$item->key] = json_decode($item->value, true);
                    } else {
                        $value[$item->key] = $item->value;
                    }
                }
            } else {
                if ($config->type == SourceConfig::TYPE_FILE) {
                    $value = FileHelper::getLink($config->value, SourceConfig::PATH_GET_FILE);
                } else if (in_array($config->type, [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
                    $value = json_decode($config->value, true);
                } else {
                    $value = $config->value;
                }
            }

            $configs[$config->key] = $value;
        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'app_config' => $configs
        ]);
    }

    public function appCustomerInfo($code)
    {
        $app = AppCustomer::query()->where('code', $code)->first();
        if (!$app) {
            return response_error('app customer not found!');
        }

        $configs = json_decode($app->configs, true);
        $data = [];
        foreach($configs as $config) {

            if ($config['is_group'] == 1) {
                foreach ($config['items'] as $item) {
                    //$child[$item['key']] = $item['value'];
                    $data[$config['key']][$item['key']] = $item['value'];
                }
            } else {
                $data[$config['key']] = $config['value'];
            }

        }

        return response()->json([
            'status' => true,
            'message' => 'success',
            'app_config' => $data
        ]);
    }
}
