<?php

namespace App\Http\Controllers\Design;

use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositores\Contracts\IDesign;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    protected $designs;
    
    public function __construct(IDesign $designs)
    {
        $this->designs = $designs;    
    }



    public function index()
    {
        // $designs = Design::all();
        $designs = $this->designs->all();

        return DesignResource::collection($designs);
    }

    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'. $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
            'tags'  => ['required']
        ]);
        

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => ! $design->upload_successfuly ? false : $request->is_live
        ]);

        // Apply the tags 
        $design->retag($request->tags);
        
        return new DesignResource($design);
    }

    public function destroy($id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('delete', $design);

        // Delete files associated to the record
        foreach(['original', 'thumbnail', 'large'] as $size)
        {
            // check if exists in database
            if(Storage::disk($design->disk)->exists("uploads/designs/{$size}/" . $design->image)){
                Storage::disk($design->disk)->delete("uploads/designs/{$size}/" . $design->image);
            }
        }

        $design->delete();

        return response()->json(["message" => "Record deleted."], 200);
    }
}
