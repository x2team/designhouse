<?php

namespace App\Http\Controllers\Design;

use App\Models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DesignController extends Controller
{
    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $this->validate($request, [
            'title' => ['required', 'unique:designs,title,'. $id],
            'description' => ['required', 'string', 'min:20', 'max:140'],
        ]);
        

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => ! $design->upload_successfuly ? false : $request->is_live
        ]);

        return response()->json($design, 200);
    }
}
