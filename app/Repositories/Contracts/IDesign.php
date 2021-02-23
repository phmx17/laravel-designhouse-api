<?php

namespace App\Repositories\Contracts;

// holds all the methods that get implemented in the repository
interface IDesign
{
  public function applyTags($id, array $data);
  public function addComment($designId, array $data);

}