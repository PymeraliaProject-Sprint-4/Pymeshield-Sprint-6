<?php

namespace App\Http\Controllers;

use App\Models\Emblem;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;
use App\Models\Course;
use Illuminate\Support\Carbon;
use Illuminate\Pagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmblemController extends Controller
{
    public function index()
    {
        $emblems = Emblem::with('course')->orderby('id', 'desc')->whereNull('hidden')->paginate(10);
        $courses = Course::all();

        return view('emblems.emblemes', compact('emblems', 'courses'));
        
    }


    public function mostrar()
    {
        $emblems = Emblem::with('course')->orderby('id', 'desc')->whereNull('hidden')->paginate(10);
        $courses = Course::all();
    
        return response()->json([
            'emblems' => $emblems,
            'courses' => $courses,
        ]);
    }

    public function store(Request $request)
{
    $emblem = new Emblem;

    $emblem->name = $request->name;
    $emblem->description = $request->description;

    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if($request->file('image')){
        $image = $request->file('image');
        $path = 'img/imatgesemblemes/';
        $filename = time() . '.' . $image->getClientOriginalName();


        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        $guardar = $request->file('image')->move($path, $filename);
        $emblem->image = $path . $filename;
    }

    $emblem->course_id = $request->course_id;

    $emblem->save();

    return redirect()->route('emblems.index');
}

    public function update(Request $request)
{
    
    $id = $request->id;
    $emblem= Emblem::find($id);
    $emblem->name = $request->name;
    $emblem->description = $request->description;
    $emblem->course_id = $request->course_id;
    

    $em = $request->file('image');
    if(!empty($em)){

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if($request->file('image')){
            $image = $request->file('image');
            $path = 'img/imatgesemblemes/';
            $filename = time() . '.' . $image->getClientOriginalName();
            $guardar = $request->file('image')->move($path, $filename);

            $emblem->image = $path . $filename;
        }
        $emblem->save();

        return redirect()->route('emblems.index');
    }   

}

    public function restaurar()
    {
        $emblems = Emblem::whereNotNull('hidden')->orderBy('id', 'desc')->paginate(10);

        return view('emblems.restaurar', compact('emblems'));
        
    }

    public function restaurarMostrar()
    {
        $emblems = Emblem::whereNotNull('hidden')->orderBy('id', 'desc')->paginate(10);
        $courses = Course::all();
        
        return response()->json([
            'emblems' => $emblems,
            'courses' => $courses,
        ]);
        
    }

    public function restaurarhide($emblem)
    {
        
        $emblem = Emblem::find($emblem);
        $emblem->hidden = null;
        $emblem->save();

        return back();
    }

    public function eliminar($emblem)
    {
        $emblem = Emblem::find($emblem);
        $emblem->hidden = Carbon::now()->format('Y-m-d');
        $emblem->save();

        $emblem = Emblem::whereNull('hidden')->orderBy('updated_at', 'desc')->get();
        
        return back();
    }
    
}
