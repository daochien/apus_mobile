<?php

namespace App\Services\Admin;

use App\Helpers\FileHelper;
use App\Models\Source;
use App\Models\SourceConfig;
use App\Repositories\Source\SourceRepositoryInterface;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZanySoft\Zip\Zip;

class SourceService
{

    protected $sourceRepo;

    protected $fileHelper;

    public function __construct(
        SourceRepositoryInterface $sourceRepo,
        FileHelper                 $fileHelper
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
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), Source::DIR_UPLOAD_IMAGE);

                if (!$image) {
                    throw new \Exception('Upload ảnh đại diện thất bại');
                }

                $source->avatar = $image;
            }

            if (!empty($attributes['source'])) {
                $file = $attributes['source'];
                $fileName = $source->code.'.'.$file->getClientOriginalExtension();
                $path = $this->fileHelper->saveFile($file, $fileName, Source::DIR_UPLOAD_SOURCE);

                if (!$path) {
                    throw new \Exception('Upload file thất bại');
                }

                $source->path = $path;
                $location = storage_path(Source::DIR_UPLOAD_SOURCE.$fileName);
                $zip = Zip::open($location);
                $zip->extract(storage_path(Source::DIR_UPLOAD_SOURCE.$source->code));
            }

            DB::commit();
            return $source->save();

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
            $source = $this->sourceRepo->findById($id);
            if (!$source) {
                throw new \Exception('Không tìm thấy thông tin source mẫu');
            }

            if (!empty($attributes['avatar'])) {
                $file = $attributes['avatar'];
                $image = $this->fileHelper->saveFile($file, $file->getClientOriginalName(), Source::DIR_UPLOAD_IMAGE);

                if (!$image) {
                    throw new \Exception('Upload ảnh đại diện thất bại');
                }

                $source->avatar = $image;
            }

            if (!empty($attributes['source'])) {
                $folderRoot = storage_path(Source::DIR_UPLOAD_SOURCE.$source->code);
                $fileRoot = storage_path("sources/".$source->path);
                Storage::delete($fileRoot);
                $fileSys = new Filesystem();
                $fileSys->cleanDirectory($folderRoot);
                $file = $attributes['source'];
                $fileName = $source->code.'.'.$file->getClientOriginalExtension();
                $path = $this->fileHelper->saveFile($file, $fileName, Source::DIR_UPLOAD_SOURCE);

                if (!$path) {
                    throw new \Exception('Upload file thất bại');
                }

                $source->path = $path;
                $location = storage_path(Source::DIR_UPLOAD_SOURCE.$fileName);
                $zip = Zip::open($location);
                $zip->extract(storage_path(Source::DIR_UPLOAD_SOURCE.$source->code));
            }

            $source->name = $attributes['name'];
            $source->desc = $attributes['desc'];
            $source->status = $attributes['status'];
            $source->save();

            if (!empty($attributes['configs'])) {
                $this->_updateConfigs($source->id, $attributes['configs']);
            }

            DB::commit();

            return $source;
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            return false;
        }
    }

    public function exportConfig($id, &$msg = '')
    {
        try {
            $source = $this->sourceRepo->findById($id);
            if (!$source) {
                throw new \Exception('Không tìm thấy thông tin source mẫu');
            }

            if (!empty($source->configs)) {
                $configs = [];
                foreach ($source->configs as $config) {
                    if ($config->type == SourceConfig::TYPE_FILE) {
                        $value = FileHelper::getLink($config->value, SourceConfig::DIR_UPLOAD_FILE);
                    } else if (in_array($config->type, [SourceConfig::TYPE_RADIO, SourceConfig::TYPE_CHECKBOX])) {
                        $value = json_decode($config->value, true);
                    } else {
                        $value = $config->value;
                    }

                    $configs[$config->key] = $value;
                }

                $fileName = 'config.json';
                $path = storage_path(Source::DIR_UPLOAD_SOURCE.$source->code."/{$fileName}");
                File::put($path, json_encode($configs));
                return true;
            }
            return false;
        } catch (\Exception $e) {
            dd($e);
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
            $insert = $this->_buildDataInsertConfig($sourceId, $config);
            $dataInserts[] = $insert;
        }

        return SourceConfig::query()->insert($dataInserts);
    }

    protected function _updateConfigs($sourceId, $configs)
    {
        if (empty($configs)) {
            return false;
        }

        $dataInserts = [];
        $idIsExists = [];
        foreach ($configs as $config) {
            if (!empty($config['id'])) {
                $update['key'] = $config['key'];
                $update['type'] = $config['type'];
                if (!empty($config['value'])) {
                    if (in_array($config['type'], ['radio', 'checkbox'])) {
                        $update['value'] = json_encode($config['value']);
                    } else {
                        $update['value'] = $config['value'];
                    }
                }
                $update['is_edit'] = !empty($config['is_edit']) ?? 0;
                SourceConfig::query()->where('id', $config['id'])->update($update);
                $idIsExists[] = $config['id'];
            } else {
                $insert = $this->_buildDataInsertConfig($sourceId, $config);
                $dataInserts[] = $insert;
            }
        }

        if (!empty($idIsExists)) {
            SourceConfig::query()->where('source_id', $sourceId)->whereNotIn('id', $idIsExists)->delete();
        }

        if (!empty($dataInserts)) {
            return SourceConfig::query()->insert($dataInserts);
        }
    }

    protected function _buildDataInsertConfig($sourceId, $config)
    {
        $insert['source_id'] = $sourceId;
        $insert['key'] = $config['key'];
        $insert['type'] = $config['type'];
        $insert['is_edit'] = !empty($config['is_edit']) ?? 0;
        if (in_array($config['type'], ['radio', 'checkbox'])) {
            $insert['value'] = json_encode($config['value']);
        } else {
            $insert['value'] = $config['value'];
        }
        return $insert;
    }

}
