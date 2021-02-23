<?php

namespace App\Repositories\Eloquent;
use App\Repositories\Contracts\IDesign;
use App\Models\Design;
use App\Repositories\Eloquent\BaseRepository;

// implements the interface and laravel expects a concrete definition of all the methods in the interface
class DesignRepository extends BaseRepository implements IDesign
{
  public function model()
  {
    return Design::class; // returns the entire namespace: App\Models\Design; this is to dynamically pass the model to BaseRepository
  }

  public function applyTags($id, array $data)
  {
    $design = $this->find($id); // extends from BaseRepository
    $design->retag($data);  // laravel method
  }

  public function addComment($designId, array $data)
  {
    // get design for which to comment on
    $design = $this->find($designId);

    // create the comment for the design
    $comment = $design->comments()->create($data);
    return $comment;
  }
}

