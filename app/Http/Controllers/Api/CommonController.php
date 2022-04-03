<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
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
}
