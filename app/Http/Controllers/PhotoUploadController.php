<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoModel;
class PhotoUploadController extends Controller
{

    public function photoUpload(Request $request, PhotoModel $model) {
	    $this->validate($request, [
	        'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg,JPG|max:2048',
	    ]);

	    if ($request->hasFile('input_img')) {
	    	$image = $request->file('input_img');
	        $imageName = $model->saveImage($image);
	        $imageInfo = $model->modifyToStandardSize($imageName);

	        return view('upload_success', compact('imageInfo')); 
	    }
	    
	}
}
