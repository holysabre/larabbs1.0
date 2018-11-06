<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * @param Request $request
     * @param ImageUploadHandler $uploader
     * @return array
     * 公共的图片上传
     */
    public function editorUpload(Request $request, ImageUploadHandler $uploader)
    {
        $return = [
            'success' => false,
            'msg' => '图片上传失败',
            'file_path' => '',
        ];
        $folder = $request->folder ? : 'default';
        $prefix = $request->prefix ? : 'default';
        $max_width = $request->max_width ? : false;
        if($file = $request->upload_file)
        {
            $result = $uploader->save($file, $folder, $prefix, $max_width);
            if($result)
            {
                $return = [
                    'success' => true,
                    'msg' => 'success',
                    'file_path' => $result['path'],
                ];
            }
        }
        return $return;
    }
}
