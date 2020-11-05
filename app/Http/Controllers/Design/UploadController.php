<?php

namespace App\Http\Controllers\Design;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'image' => ['required', 'mimes:jpg,jpeg,png,gif', 'max:1024']
        ]);

        // get image
        $image = $request->file('image');
        $image_path = $image->getPathName(); 
        $file_name = time() . '_' . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // Move image to temporary location (tmp)
        $tmp = $image->storeAs('tmp/original', $file_name, 'tmp');

        // create database record for design
        $design = auth()->user()->designs()->create([
            'image' => $file_name,
            'disk'  => config('site.upload_disk')
        ]);

        // dispatch a job to handle the image manipulation
        $this->dispatch(new UploadImage($design));

        return response()->json($design, 200);

    }
}
