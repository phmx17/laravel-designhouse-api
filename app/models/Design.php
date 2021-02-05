<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
  protected $fillable=[
    'user_id',
    'image',
    'title',
    'desciption',
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
}
