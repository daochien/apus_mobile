<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FileHelper
{
    public static function writeFile($data, $fileName, $uploadDir = "/json/")
    {

        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }
        $uploadPath = storage_path("app/public{$uploadDir}"); // Thư mục upload

        $fullPath = $uploadDir.$fileName;

        if(Storage::disk('public')->put($fullPath, json_encode($data))) {
            return route('translation.download', ['file_name' => $fullPath]);
        }
        return false;
    }
    /**
     * saveFile
     *
     * @param $file
     * @param string $uploadDir
     * @param $fileName
     * @return mixed
     */
    public static function saveFile($file, $fileName, $uploadDir = "app/public/image-uploaded/")
    {
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0777, true, true);
        }
        $uploadPath = storage_path($uploadDir); // Thư mục upload
        $file->move($uploadPath, $fileName);
        return $fileName;
    }

    /**
     * Generate File Name.
     * @param $file
     * @return string
     */
    public static function generateFileName($file)
    {
        $pathInfo = pathinfo($file->getClientOriginalName());
        return time() . md5($pathInfo['filename']) . '.' . $pathInfo['extension'];
    }

    /**
     * Delete file.
     * @param $fileName
     */
    public static function deleteFile($fileName)
    {
        $folder = env('APP_PHOTO_DIR', '/image-uploaded/');
        Storage::delete("/public{$folder}{$fileName}");
    }

    /**
     * Get link image.
     * @param $fileName
     * @return string
     */
    public static function getLink($fileName, $folder)
    {
        $folder = env('APP_PHOTO_DIR', $folder);

        return URL::to("/storage{$folder}{$fileName}");
    }

    /**
     * Check exist image.
     * @param $fileName
     * @return bool
     */
    public static function exist($fileName)
    {
        $folder = env('APP_PHOTO_DIR', '/image-uploaded/');
        return file_exists(storage_path("app/public{$folder}" . $fileName));
    }
}
