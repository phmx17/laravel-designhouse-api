<?php

namespace App\Jobs;

use Image;
use File;
use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Tymon\JWTAuth\Contracts\Providers\Strorage;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
      $this->design = $design;
    }

    /**
     * Execute the job. This handles all the login and will execute
     *
     * @return void
     */
    public function handle()
    {
      $disk = $this->design->disk;  // arg gets passed into constructor
      $filename = $this->design->image;
      $original_file = storage_path() . '/uploads/original/' . $filename;

      try{
        // create large image from original and save to tmp disk
        Image::make($original_file)
          ->fit(800, 600, function($constraint) {
            $constraint->aspectRatio();
          })
          ->save($large = storage_path('uploads/large/' . $filename));
        
        // create the thumbnail from original and save to tmp disk
        Image::make($original_file)
          ->fit(250, 200, function($constraint) {
            $constraint->aspectRatio();
          })
          ->save($thumbnail = storage_path('uploads/thumbnail/' . $filename));

        /* store images to permanent disk */
        // original image        
        if (Storage::disk($disk)  // using if block for write success
          ->put('uploads/designs/original/' . $filename, fopen($original_file, 'r+'))){ // fopen() r+ = add read and write
            File::delete($original_file); 
        } 

        // large image
        if (Storage::disk($disk)
          ->put('uploads/designs/large/' . $filename, fopen($large, 'r+'))){
            File::delete($large); // delete temp file
        } 

        // thumbnail image
        if (Storage::disk($disk)
          ->put('uploads/designs/thumbnail/' . $filename, fopen($thumbnail, 'r+'))){
            File::delete($thumbnail); // delete temp file
        } 
        
        // update database records with success flag
        $this->design->update([
          'upload_successful' => true
        ]);
        } catch(\Exception $e){
            \Log::error($e->getMessage());
        }

    }
}
