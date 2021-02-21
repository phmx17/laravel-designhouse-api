<?php

namespace App\Http\Controllers\Designs;

use App\Models\Design;
use App\Repositories\Contracts\IDesign; // Design Interface = contract; for use with Repository Pattern
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{ 
  // inject the Repository Contracts with a php magic constructor

  protected $designs;
  public function __construct(IDesign $designs) // injecting contract class into constructor; pull in as many here as you like
  {
    $this->designs = $designs;
  }

  public function index()
  { 
    // implementing Repository Pattern
    // $designs = Design::all(); // old version
    $designs = $this->designs->all(); // new version with Repository pattern where the function definition lies in the individual Repository files; not here anymore
    return DesignResource::collection($designs);  // as opposed to single instance below: return new DesignResource($design);
  }

  public function findDesign($id)
  {
    $design = $this->designs->find($id);  // this method is declared in IBase interface and defined in BaseRepository
    return new DesignResource($design);  // convert resource to array
  }

  public function update(Request $request, $id)
  {
    $design = $this->designs->find($id);
    $this->authorize('update', $design);  // policy authorize update(); $design = resource

    $this->validate($request, [
      'title' => ['required', 'unique:designs,title,'. $id],  // .$id = excluding from validation rule
      'description' => ['required', 'string', 'min:20', 'max:140'],
      'tags' => ['required']
    ]);    

    $design = $this->designs->update($id, [
      'title' => $request->title,
      'description' => $request->description,
      'slug' => Str::slug($request->title), // 'hello world' => 'hello-world'
      'is_live' => !$design->upload_successful ? false : $request->is_live,
    ]);
    
    // apply the tags
    $this->designs->applyTags($id, $request->tags); // interface method; removes all current tags (detag()) and retags the model instance;

    return new DesignResource($design);
  }

  public function destroy($id)
  {
    $design = $this->designs->find($id); // before Repository Interface: Design::findOrFail($id);  // exit if not found
    $this->authorize('delete', $design);  // policy authorize delete(); $design = resource
    
    // delete all files associated to the design
    foreach(['thumbnail', 'large', 'original'] as $size){
      // check if the file exists in the database
      if (Storage::disk($design->disk)->exists("uploads/designs/{$size}/" . $design->image)){
        Storage::disk($design->disk)->delete("uploads/designs/{$size}/" . $design->image);
      }      
    }
    $this->designs->delete();
    return response()->json(['message' => 'Design has been deleted'], 200);
  }
}
