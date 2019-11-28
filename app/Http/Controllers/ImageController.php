<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use Image;

class ImageController extends Controller
{
    public function uploadImgFileApi(Request $request)
    {
        $file=$_FILES['file'];
        $fileType=pathinfo($file['name'],PATHINFO_EXTENSION);
        $ext=strtolower($fileType);
        $allowed_extensions = array("jpg", "bmp", "gif", "tif","png","jpeg");
        if ($ext && !in_array($ext, $allowed_extensions)) {
            return Response::json([ 'errors' => '只能上传png、jpg、gif、等等文件.']);
        }
        $destinationPath = config('enroll.image_path');
        $fileName = str_random(16).'.'.$fileType;
        move_uploaded_file($file["tmp_name"],public_path($destinationPath.$fileName));
        $img = Image::make(public_path($destinationPath.$fileName))
                    ->resize(640, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
        $img->save(public_path($destinationPath.$fileName));
        $upload_prefix = config('enroll.image_url');
        $imageSrcs=$upload_prefix.$fileName;
   
        return Response::json(
            [
                'success' => true,
                'status' => true,
                'imageSrcs' =>$imageSrcs
            ]
        );
    }
}
