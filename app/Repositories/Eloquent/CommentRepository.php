<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Contracts\IComment;
use App\Models\Comment;
use App\Repositories\Eloquent\BaseRepository;

// implements the interface and laravel expects a concrete definition of all the methods in the interface
class CommentRepository extends BaseRepository implements IComment
{
  public function model()
  {
    return Comment::class; // returns the entire namespace: App\Models\User; this is to dynamically pass the model to BaseRepository
  }
}
