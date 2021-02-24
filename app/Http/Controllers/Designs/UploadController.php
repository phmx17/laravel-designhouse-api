<?php

namespace App\Http\Controllers\Designs;

use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\IDesign;

class UploadController extends Controller
{
  // implementing Interface for Repositories
  protected $designs;

  public function __construct(IDesign $designs)
  {
      $this->designs = $designs;
  }  

  public function upload(Request $request) 
  {
    dd($request->all());
    $this->validate($request, [
      'image' => ['required', 'mimes:jpeg,gif,bmp,png', 'max:2048']
  ]); 

    //get the image
    $image = $request->file('image');
    $image_path = $image->getPathName();

    // get original filename and replace spaces with '_' and add timestamp
    $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

    // move image to temp location disk called 'tmp' in \config\filesystem
    $tmp = $image->storeAs('uploads/original', $filename, 'tmp');

    // create the database record for the design
    $design = $this->designs->create([
      'image' => $filename,
      'disk' => config('site.upload_disk'),  // see in \config\site.php      
    ]);

    // dispatch job to handle image manipulation
    $this->dispatch(new UploadImage($design));  // php artisan make:job UploadImage
    return response()->json($design, 200);
  }
}
