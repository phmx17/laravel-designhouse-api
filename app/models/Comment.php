<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $fillable = [
    'body',
    'user_id'
  ];

  // polymorphic
  public function commentable()
  {
    return $this->morphTo();  // make the relationship in the Design model
  }




  public function user()
  {
    $this->belongsTo(User::class);  // go do the inverse !
  }
}
