<?php

namespace App\Repositories\Eloquent\Criteria;
use App\Repositories\Criteria\ICriterion;

class isLive implements ICriterion
{
  public function apply($model)
  {
    // return the newest first
    return $model->where('is_live', true);  
  }
}