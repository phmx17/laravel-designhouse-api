<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentTaggable\Taggable;  // trait for cviebrock taggable package

class Design extends Model
{
  use Taggable;
  protected $fillable=[
    'user_id',
    'image',
    'title',
    'description',
    'slug',
    'close_to_comment',  // ability for people comment or not
    'is_live',
    'upload_successful',
    'disk' // public or amazon s3
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  /**
   * provide image path for public (local) storage or s3
   * returns thumbnail as well as large and original image
   */
  public function getImagesAttribute()
  {
    return [
      'thumbnail' => $this->getImagePath('thumbnail'),
      'large' => $this->getImagePath('large'),
      'original' => $this->getImagePath('original'),
    ];
  }

  // refactor so that don't have to repeat above
  protected function getImagePath($size)  // image size
  {
    return Storage::disk($this->disk)->url('uploads/designs/{$size}/' . $this->image);
  }
}
