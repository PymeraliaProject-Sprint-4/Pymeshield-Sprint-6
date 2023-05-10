<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\CourseUser;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\fileExists;

class CourseController extends Controller
{
    public function index()
    {
        return view('course.index');
    }

    public function coursecategories()
    {
        return view('course.coursecategories');
    }

    public function index_data()
    {
        return Course::whereNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();
    }

    public function getImage($id)
    {
        $course = Course::findOrFail($id);
        $imagePath = 'img/imatgescursos/' . $course->image;
        $imageUrl = asset($imagePath);

        return response()->json(['url' => $imageUrl]);
    }



    public function index_data_categories()
    {
        $postsperpage = 7;
        return Category::select('categories.id', 'categories.name', 'categories.course_id', 'courses.name as course_name')
            ->join('courses', 'categories.course_id', '=', 'courses.id')
            ->orderBy('categories.updated_at', 'desc')
            ->paginate($postsperpage);
    }

    public function allCourses()
    {
        return Course::whereNull('hidden')
            ->get();
    }

    public function allCategories(){
        return Category::whereNull('hidden')
            ->get();
    }

    public function CategoryDelete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }

    public function users()
    {
        return User::whereNull('hidden')->orderBy('updated_at', 'desc')->get();
    }

    public function hidden()
    {
        return view('course.hidden');
    }

    public function hidden_data()
    {
        return Course::whereNotNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();
    }

    public function index_client()
    {
        $cursos = Course::whereNull('hidden')->orderBy('updated_at', 'desc')->get();

        return view('course.client.index', compact('cursos'));
    }

    public function client_data()
    {
        return Course::whereNull('hidden')->orderBy('updated_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        // Guardar el nuevo curso en la base de datos
        $course = new Course;
        $course->name = strip_tags($request->name);
        $course->description = strip_tags($request->description);
        $course->created_at = now();
        $course->save();
        $courseId = $course->id;
        // Obtener los ids de los usuarios del array $request->users
        $userIds = $request->selectedUsers;

        // Crear registros en la tabla course_user para cada usuario del curso
        foreach ($userIds as $userId) {
            $courseUser = new CourseUser;
            $courseUser->course_id = $course->id;
            $courseUser->user_id = $userId;
            $courseUser->save();
        }

        // Obtener todos los cursos para actualizar la tabla
        $courses =  Course::whereNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();



        // Devolver los cursos actualizados en formato JSON
        return response()->json([
            'id' => $courseId,
            'courses' => $courses,
        ]);
    }

    public function createCategory(Request $request)
    {

        // Guardar la nueva categoría en la base de datos
        $currentTime = Carbon::now();

        $category = new Category;
        $category->name = $request->name;
        $category->course_id = $request->selectedCourse; // agregando la relación con el curso seleccionado
        $category->hidden = $currentTime->toDateTimeString();
        $category->created_at = now();
        $category->save();

        return response()->json(['message' => 'Categoría creada'], 200);
    }

    public function createActivity(Request $request)
    {
        // Guardar la nueva categoría en la base de datos
        $currentTime = Carbon::now();

        $activity = new Activity;
        $activity->name = $request->name;
        $activity->description = $request->description;
        $activity->start_date = $request->start_date;
        $activity->final_date = $request->end_date;
        $activity->category_id = $request->selectedCategory; // agregando la relación con el curso seleccionado
        $activity->hidden = $currentTime->toDateTimeString();
        $activity->created_at = now();
        $activity->save();

        return response()->json(['message' => 'Categoría creada'], 200);
    }

    public function updateCategory($id, Request $request)
    {
        $category = Category::find($id);
        $category->name = strip_tags($request->name_edit);
        $category->course_id = $request->selectedCourse;
        $category->save();
        return response()->json(['courses' => $category->course]);
    }

    public function restore($id)
    {
        $course = Course::find($id);
        $course->update(['hidden' => NULL]);
        return back()->with('success', 'Curso recuperado con éxito');
    }


    public function hide($id)
    {
        $course = Course::find($id);
        $course->hidden = Carbon::now()->format('Y-m-d');
        $course->save();
        $courseId = $course->id;

        $courses =  Course::whereNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();

        return response()->json([
            'id' => $courseId,
            'courses' => $courses,
        ]);
    }

    public function unHide($id)
    {
        $course = Course::find($id);
        $course->hidden = null;
        $course->save();
        $courseId = $course->id;

        $courses =  Course::whereNotNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();
        return response()->json([
            'id' => $courseId,
            'courses' => $courses,
        ]);
    }

    public function update($id, Request $request)
    {
        $course = Course::findOrFail($id);

        $this->validate($request, [
            'name_edit' => 'required|max:50',
            'description_edit' => 'required|max:255',
        ]);

        // Handle user assignments
        $userIds = $request->input('selectedUsers');
        if (!is_array($userIds)) {
            // Convertir a un array de enteros si es necesario
            $userIds = explode(',', $userIds);
            $userIds = array_map('intval', $userIds);
        }
        // Verificar que los valores de user_id son válidos
        $validUserIds = User::whereIn('id', $userIds)->pluck('id');
        if (count($validUserIds) !== count($userIds)) {
            // Algunos valores de user_id son inválidos, manejar el error aquí
        }
        $course->users()->sync($validUserIds); // Sync selected users


        // Update name and description
        $course->name = $request->input('name_edit');
        $course->description = $request->input('description_edit');

        // Handle image upload
        if ($request->hasFile('image_edit')) {
            // Delete old image, if it exists
            $oldImagePath = public_path('img/imatgescursos/') . $course->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Upload new image
            $image = $request->file('image_edit');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/img/imatgescursos/', $filename);
            $course->image = str_replace('public/', '/storage/', $path);
        }


        $course->save();

        // Get updated course list and return as JSON
        $courses = Course::orderBy('updated_at', 'desc')
            ->with('users')
            ->get();
        return response()->json(['courses' => $courses]);
    }





    public function update_hidden($id, Request $request)
    {
        $course = Course::find($id);
        $course->name = strip_tags($request->name_edit);
        $course->description = strip_tags($request->description_edit);
        $course->save();
        $courseId = $course->id;

        $userIds = $request->selectedUsers;

        // Crear registros en la tabla course_user para cada usuario del curso
        foreach ($userIds as $userId) {
            if (isset($userId)) {
                $courseUser = new CourseUser;
                $courseUser->course_id = $course->id;
                $courseUser->user_id = $userId;
                $courseUser->save();
            }
        }

        $course->users()->sync(array_map('intval', $userIds));

        // Obtener todos los cursos para actualizar la tabla
        $courses =  Course::whereNotNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();

        // Devolver los cursos actualizados en formato JSON
        return response()->json([
            'id' => $courseId,
            'courses' => $courses,
        ]);
    }

    public function course_User()
    {
        if (auth()->user()->type == 'client') {
            $data = DB::table('course_user')
                ->join('courses', 'courses.id', '=', 'course_user.course_id')
                ->select('courses.id', 'courses.name', 'courses.description')
                ->where('user_id', '=', auth()->user()->id)
                ->groupBy('courses.id', 'courses.name', 'courses.description', 'course_user.updated_at')
                ->get();

            return response()->json($data);
        } else {
            $data = DB::table('courses')
                ->select('courses.id', 'courses.name', 'courses.description')
                ->groupBy('courses.id', 'courses.name', 'courses.description')
                ->get();

            return response()->json($data);
        }
    }

    public function store2(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
            'users' => 'required|json',
        ]);

        $validated['users'] = json_decode($validated['users'], true);

        if($request->file('image')){
            $imageName = $request->file('image');
            $path  = 'img/imatgescursos/';
            //Per a evitar que pete si no detecta la carpeta
            $filename = time() . '.' . $imageName->getClientOriginalName();
                if (!file_exists(public_path($path))){
                    mkdir(public_path($path), 0755, true);
                    }
                $guardar = $request->file('image')->move($path, $filename);
                $validated->image = $path . $filename;
        }
        // Crea el nou curso e la base de dades
        $course = new Course();
        $course->name = $validated['name'];
        $course->description = $validated['description'];
        $course->image = str_replace('public/', '/storage/', $path);
        $course->save();

        // Guardar los usuarios del curso
        if (isset($validated['users'])) {
            foreach ($validated['users'] as $userId) {
                $courseUser = new CourseUser();
                $courseUser->course_id = $course->id;
                $courseUser->user_id = $userId;
                $courseUser->save();
            }
        }


        // Agafa tots els cursos per actualizar la taula
        $courses =  Course::whereNull('hidden')
            ->orderBy('updated_at', 'desc')
            ->with(['users' => function ($query) {
                $query->select('user_id');
            }])
            ->get();

        // Torna els cursos actualizats en format JSON
        return response()->json([
            'id' => $course->id,
            'courses' => $courses,
        ]);
    }
}
