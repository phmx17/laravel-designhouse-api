<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\UploadImage;

class UploadController extends Controller
{
  public function upload(Request $request) 
  {
    $this->validate($request, [
      'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048 ']  // mimes (plural)
    ]);

    //get the image
    $image = $request->file('image');
    $image_path = $image->getPathName();

    // get original filename and replace spaces with '_' and add timestamp
    $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

    // move image to temp location disk called 'tmp' in \config\filesystem
    $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

    // create the database record for the design
    $design = auth()->user()->designs()->create([
      'image' => $filename,
      'disk' => config('site.upload_disk'),  // see in \config\site.php      
    ]);

    // dispatch job to handle image manipulation
    $this->dispatch(new UploadImage($design));  // php artisan make:job UploadImage
    return response()->json($design, 200);
  }
}
