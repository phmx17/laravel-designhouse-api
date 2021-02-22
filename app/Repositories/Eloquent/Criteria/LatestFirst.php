<?php

namespace App\Repositories\Criteria;
use App\Repositories\Criteria\ICriterion;

class LatestFirst implements ICriterion
{
  public function apply($model)
  {
    // return the newest first
    return $model->latest();  // same as $model->orderBy('created_at')
  }
}