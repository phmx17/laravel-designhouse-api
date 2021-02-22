<?php

namespace App\Repositories\Eloquent;
use Illuminate\Support\Arr;
use App\Exceptions\ModelNotDefined;
use App\Repositories\Contracts\IBase;
use App\Repositories\Criteria\ICriteria;

// to be extended in other repos in order to prevent duplication 
abstract class BaseRepository implements IBase, ICriteria
{

  protected $model; // for the constructor

  public function __construct()
  {
    $this->model = $this->getModelClass();  // be able to pass classes dynamically; see at bottom
  }

  public function all()
  {
    return $this->model->all(); // dynamically pass the model for each caller
  }
  public function find($id)
  {
    $result = $this->model->findOrFail($id);
    return $result;
  }

  public function findWhere($column, $value)
  {
    return $this->model->where($column, $value)->get();
  }

  public function findWhereFirst($column, $value)
  {
     return $this->model->where($column, $value)->firstOrFail();
  }

  public function paginate($perPage = 10)
  {
    return $this->model->paginate($perPage);
  }

  public function create(array $data)
  {
    return $this->model->create($data);
  }

  public function update($id, array $data)
  {
    $record = $this->find($id); // see above
    $record->update($data);
    return $record;
  }

  public function delete($id)
  {
    $record = $this->find($id); // see above
    return $record->delete;
  }

  public function withCriteria(...$criteria)
  {
    $criteria = Arr::flatten($criteria);

    foreach($criteria as $criterion){
      $this->model = $criterion->apply($this->model); // same as $model->orderBy('created_at')
    }
    return $this;
  }


  // be able to pass classes dynamically; called from constructor
  protected function getModelClass()  
  {
    if (!method_exists($this, 'model')) // the connection here is that the subclasses each extend this Base class
    {
      throw new ModelNotDefined; // must have this model() method; our custom exception that gets handled in the Exception handler
    }
    return app()->make($this->model()); // make instance of class and call model()
  }

}