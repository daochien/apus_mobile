<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function __construct()
    {
    }

    public function upload(Request $request)
    {
        if (!empty($request->has('file'))) {
            $file = $request->file('file');
            $image = FileHelper::saveFile($file, $file->getClientOriginalName(), '/source/configs/file/');
            if (!$image) {
                return response_error('Upload file thất bại');
            }
            return response_success('success', [
                'path' => FileHelper::getLink($image, '/source/configs/file/'),
                'file_name' => $image
            ]);
        }

        return response_error('Upload file thất bại');
    }
}
