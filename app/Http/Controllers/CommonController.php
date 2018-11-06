<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function editorUpload(Request $request, ImageUploadHandler $uploader)
    {
        $folder = $request->folder ? : 'default';
        $prefix = $request->prefix ? : 'default';
        $max_width = $request->max_width ? : false;
        $result = $uploader->save($request->upload_file, $folder, $prefix, $max_width);
        if($result)
        {
            return response()->json([
                'success' => true,
                'msg' => 'success',
                'file_path' => $result['path'],
            ],200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'msg' => 'fail',
                'file_path' => '',
            ],200);
        }
    }
}
