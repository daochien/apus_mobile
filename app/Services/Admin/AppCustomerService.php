<?php

namespace App\Services\Admin;

use App\Models\AppCustomer;
use App\Models\Package;
use App\Models\Source;
use App\Models\SourceConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

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
            $this->_cloneSource($appCus->code, $package->source->code);

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
        $dirCopy = 'storage/app_customers/';
        if (!File::exists($dirCopy)) {
            File::makeDirectory($dirCopy, 0777, true, true);
        }

        $rootPath = public_path("storage/sources/{$sourceCode}");
        $copyPath = public_path("{$dirCopy}/{$appCode}");
        File::copyDirectory($rootPath, $copyPath);
        //create file config.json
        $fileName = 'config.json';

        File::put("{$copyPath}/{$fileName}", json_encode([
            'app_customer_code' => $appCode
        ]));

        //zip folder copy
        $filePath = "{$dirCopy}/{$appCode}.zip";
        $zip = new \ZipArchive();
        if ($zip->open($filePath, \ZipArchive::CREATE) !== true) {
            throw new \RuntimeException('Cannot open ' . $filePath);
        }

        $this->_addContent($zip, $copyPath);
        $zip->close();
    }

    /**
     * This takes symlinks into account.
     *
     * @param ZipArchive $zip
     * @param string     $path
     */
    protected function _addContent(\ZipArchive $zip, string $path)
    {
        /** @var SplFileInfo[] $files */
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::FOLLOW_SYMLINKS
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        while ($iterator->valid()) {
            if (!$iterator->isDot()) {
                $filePath = $iterator->getPathName();
                $relativePath = substr($filePath, strlen($path) + 1);

                if (!$iterator->isDir()) {
                    $zip->addFile($filePath, $relativePath);
                } else {
                    if ($relativePath !== false) {
                        $zip->addEmptyDir($relativePath);
                    }
                }
            }
            $iterator->next();
        }
    }
}
