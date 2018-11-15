<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index(){
        return view('images.image_upload');
    }

    public function ajaxImage(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                'file' => 'image',
            ],
            [
                'file.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)'
            ]);

        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
            );

        return $this->move_image($request->file);

    }
    private function move_image($file){
        $extension = $file->getClientOriginalExtension();
        $dir = 'uploads/';
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $file->move($dir, $filename);
        return $filename;
    }

    public function deleteImage($filename)
    {
        File::delete('uploads/' . $filename);
    }
}