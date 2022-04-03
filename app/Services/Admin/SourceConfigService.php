<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Models\Source;
use App\Models\SourceConfig;
use App\Models\SourceConfigItem;
use Illuminate\Support\Facades\DB;
use ZanySoft\Zip\Zip;

class SourceConfigService
{
    protected $fileHelper;

    public function __construct(
        FileHelper $fileHelper
    )
    {
        $this->fileHelper = $fileHelper;
    }

    public function store($attributes, &$msg)
    {
        DB::beginTransaction();
        try {

            if ($attributes['is_group'] == SourceConfig::NOT_IS_GROUP) {
                $dataCreate = $this->_buildDataCreateNotIsGroup($attributes);
                $sourceConfig =  SourceConfig::query()->create($dataCreate);
            } else {
                $dataCreate = $this->_buildDataCreateIsGroup($attributes);
                $sourceConfig = SourceConfig::query()->create($dataCreate);
                $items = $this->_buildConfigItems($sourceConfig->id, $attributes);
                SourceConfigItem::query()->insert($items);
            }

            DB::commit();

            return $sourceConfig;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }

    public function update($id, $attributes, &$msg)
    {
        DB::beginTransaction();
        try {
            $config = SourceConfig::query()->findOrFail($id);

            if ($attributes['is_group'] == SourceConfig::NOT_IS_GROUP) {
                $dataCreate = $this->_buildDataCreateNotIsGroup($attributes);
                $config->update($dataCreate);
            } else {
                $dataCreate = $this->_buildDataCreateIsGroup($attributes);
                $config->update($dataCreate);

                list($dataInsert, $dataUpdate, $deletedIds) = $this->_buildDataUpdateConfigItems($config->id, $attributes);
                if (!empty($dataUpdate)) {
                    SourceConfigItem::query()->upsert($dataUpdate, ['id'], ['config_id', 'key', 'type', 'value', 'is_edit']);
                }

                if (!empty($deletedIds)) {
                    SourceConfigItem::query()->where('config_id', $config->id)->whereNotIn('id', $deletedIds)->delete();
                }

                if (!empty($dataInsert)) {
                    SourceConfigItem::query()->insert($dataInsert);
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }

    protected function _buildDataCreateNotIsGroup($attributes)
    {
        $dataCreate['source_id'] = $attributes['source_id'];
        $dataCreate['key'] = $attributes['key'];
        $dataCreate['type'] = $attributes['type'];
        if (in_array($attributes['type'], [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
            $dataCreate['value'] = json_encode($attributes['value']);
        } else {
            $dataCreate['value'] = $attributes['value'];
        }
        $dataCreate['is_edit'] = !empty($attributes['is_edit']) ?? 0;
        $dataCreate['is_group'] = $attributes['is_group'];
        return $dataCreate;
    }

    protected function _buildDataCreateIsGroup($attributes)
    {
        $dataCreate['source_id'] = $attributes['source_id'];
        $dataCreate['key'] = $attributes['key'];
        $dataCreate['is_group'] = $attributes['is_group'];
        return $dataCreate;
    }

    protected function _buildConfigItems($configId, $attributes)
    {
        $items = [];

        foreach ($attributes['config_items'] as $item) {
            if (in_array($item['type'], [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
                $value = json_encode($item['value']);
            } else {
                $value = $item['value'];
            }
            $items[] = [
                'config_id' => $configId,
                'key' => $item['key'],
                'type' => $item['type'],
                'value' => $value,
                'is_edit' => !empty($item['is_edit']) ?? 0
            ];
        }

        return $items;
    }

    protected function _buildDataUpdateConfigItems($configId, $attributes)
    {
        $dataUpdate = [];
        $dataInsert = [];
        $deletedIds = [];
        foreach ($attributes['config_items'] as $item) {
            if (isset($item['id'])) {
                if (in_array($item['type'], [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
                    $value = json_encode($item['value']);
                } else {
                    $value = $item['value'];
                }
                $dataUpdate[] = [
                    'id' => $item['id'],
                    'config_id' => $configId,
                    'key' => $item['key'],
                    'type' => $item['type'],
                    'value' => $value,
                    'is_edit' => !empty($item['is_edit']) ?? 0
                ];
                $deletedIds[] = $item['id'];
            } else {
                if (in_array($item['type'], [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
                    $value = json_encode($item['value']);
                } else {
                    $value = $item['value'];
                }
                $dataInsert[] = [
                    'config_id' => $configId,
                    'key' => $item['key'],
                    'type' => $item['type'],
                    'value' => $value,
                    'is_edit' => !empty($item['is_edit']) ?? 0
                ];
            }
        }

        return [$dataInsert, $dataUpdate, $deletedIds];
    }
}
