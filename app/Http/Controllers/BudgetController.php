<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;


class BudgetController extends Controller
{
    //Retornar la vista dels pressupostos
    public function AceptarPresupuestos()
    {

        $budgets = Budget::paginate();
        return view('Presupuestos.Asignacion_Precios.asignacionPrecios', compact('budgets'));
    }

    public function aceptarPresupuesto()
    {

        return view('Presupuestos.Asignacion_Precios.AcceptarPresupostos');
    }

    //Retornar la vista de crear un nou pressupost
    public function CrearPresupuestos()
    {

        return view('Presupuestos.Asignacion_Precios.crearPrecio');
    }
    //Accio per a crear un nou pressupost
    public function Guardar(Request $request)
    { //Le paso $request para que me recoja los datos del formulario
        $budgets = new Budget();

        $budgets->created_at = $request->created_at; //Paso los datos introducidos en el formulario en cada campo de la BBD
        $budgets->hidden = $request->hidden;
        $budgets->price = $request->price;
        $budgets->status = $request->status;
        $budgets->accepted = $request->accepted;

        $budgets->save(); //Guardo los valores en la base de datos con la funcion save()

        return redirect()->route('Aceptar-Presupuestos');
    }

    //Retornar la vista de editar el preu
    public function Editar(Budget $id)
    {

        return view('Presupuestos.Asignacion_Precios.editarPrecio', compact('id'));
    }

    //Acció per a Actualitzar els valors a la base de dades
    public function Update(Request $request, Budget $id)
    { //Le paso $request para que me recoja los datos del formulario

        $id->created_at = $request->created_at;
        $id->hidden = $request->hidden;
        $id->price = $request->price;
        $id->status = $request->status;
        $id->accepted = $request->accepted;

        $id->save(); //Guardo los valores en la base de datos con la funcion save()
        return redirect()->route('Aceptar-Presupuestos'); //Redirigo al usuario a la pagina
    }

    public function destroy($id) //Acción con el metodo destroy pasandole la id de la fila que quiero eliminar
    {
        $budget = Budget::findOrFail($id);
        $budget->delete(); //Asigno a la variable budget el metoo delete

        return redirect()->route('Aceptar-Presupuestos')
            ->with('success', 'Presupuesto eliminado exitosamente.');
    }


    //Vista pagina crear presupuesto
    public function crearPresupuesto()
    {
        return view('Presupuestos.ejemplos_crud.crear');
    }


    //READ - Leer - Listar

    public function index()
    {
        return view('Presupuestos/show_budgets_admin_view/index');
    }

    public function indexClient()
    {
        return view('Presupuestos/show_budgets_client_view/index');
    }

    public function listAllBudgets()
    {
        if(auth()->user()->type == "client") //si un client intenta accedir aquesta petició no rep res
        {

        }else{
            $data = DB::table('tasks')
                ->join('users', 'users.id', '=', 'tasks.user_id')
                ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->select("users.name", "users.last_name", "companies.name as company_name", "budgets.created_at", "budgets.updated_at", "budgets.status", "budgets.id")
                ->orderBy('budgets.created_at', 'desc')
                ->groupBy('budgets.id')->get();
                return response()->json($data); //Retornar json vara vue
        }
    }

    public function listClientBudgets()
    {

        $idclient = auth()->user()->id;
        $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->select("budgets.created_at", "budgets.updated_at", "budgets.status", "budgets.id")
            ->where('users.id', '=', $idclient)
            ->orderBy('budgets.created_at', 'desc')
            ->groupBy('budgets.id')->get();
            return response()->json($data); //Retornar json vara vue

    }


    //GUARDAR - Crear - Store
    public function store(Request $request)
    {

        $pressupost = Budget::create([
            'price' => $request->price, //el parametro del objeto $request tiene que tener el mismo nompre que el atributo name del input que queremos que recoja
        ]);

        return redirect()->route('mostrar-pressupostos'); //redirigimos hacia la página

        //return $request; //recupera todo lo que enviamos
    }

    public function showAcceptModify(Request $request)
    {
            $pagination = $request->id;
            $id_budget = $request->budget;

            if(!$pagination) $pagination = 999;


            $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select('tasks.id AS id', 'answers.name', 'answers.recommendation', 'impacts.name as peligro', 'tasks.manages', 'tasks.price', 'tasks.price_customer')
            ->where('tasks.budget_id', '=', $id_budget)
            ->orderBy('tasks.id')
            ->paginate($pagination);

            return response()->json($data);
    }

    public function showTasksKivy()
    {
        if(auth()->user()->type == 'client'){

            $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select('tasks.id AS id', 'answers.name', 'answers.recommendation', 'impacts.name as danger', 'tasks.manages', 'tasks.price', 'tasks.price_customer')
            ->where('tasks.user_id', '=', auth()->user()->id)
            ->orderBy('tasks.id')
            ->get();

            return response()->json($data);
        }
        else{

            $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select('tasks.id AS id', 'answers.name', 'answers.recommendation', 'impacts.name as danger', 'tasks.manages', 'tasks.price', 'tasks.price_customer')
            ->orderBy('tasks.id')
            ->get();

            return response()->json($data);
        }


    }

    public function showBudgets()
    {
        if(auth()->user()->type == 'client'){
            $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->select('budgets.id', 'budgets.price', 'budgets.accepted')
            ->where('tasks.user_id', '=', auth()->user()->id)
            ->groupBy('budgets.id')
            ->get();

        return response()->json($data);
        }
        else{
            $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->select('budgets.id', 'budgets.price', 'budgets.accepted')
            ->groupBy('budgets.id')
            ->get();

            return response()->json($data);
        }

    }

    public function search(Request $request)
    {
        $buscado = $request->buscado;
        $id_budget = $request->budget;

        $data = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select('tasks.id', 'answers.name', 'answers.recommendation', 'impacts.name as peligro', 'tasks.manages', 'tasks.price')
            ->where('tasks.budget_id', '=', '1')
            ->where('answers.name', 'like', '%' . $buscado . '%')
            ->orWhere('answers.recommendation', 'like', '%' . $buscado . '%')
            ->where('tasks.budget_id', '=', $id_budget)
            ->get();
        if ($data == null) {
            return null;
        } else {
            return $data;
        }
        // return Task::all();
    }
    public function getTotal(Request $request)
    {
        $id_budget = $request->budget;

        $dataPyme = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select(DB::raw('ROUND(SUM(tasks.price), 2) as total'))
            ->where('tasks.budget_id', '=', $id_budget)
            ->where('tasks.manages', '=', 'Me aconseja Pymeralia')
            ->groupBy('tasks.budget_id')
            ->where('tasks.budget_id', '=', $id_budget)
            ->get();

        $dataYo = DB::table('tasks')
            ->join('users', 'users.id', '=', 'tasks.user_id')
            ->join('questionnaires', 'questionnaires.id', '=', 'tasks.questionnaire_id')
            ->join('answers', 'answers.id', '=', 'tasks.answer_id')
            ->join('budgets', 'budgets.id', '=', 'tasks.budget_id')
            ->join('impacts', 'impacts.id', '=', 'tasks.impact_id')
            ->select(DB::raw('ROUND(SUM(tasks.price_customer), 2) as total'))
            ->where('tasks.budget_id', '=', $id_budget)
            ->where('tasks.manages', '=', 'Me lo gestiono yo')
            ->groupBy('tasks.budget_id')
            ->where('tasks.budget_id', '=', $id_budget)
            ->get();

        if ($dataPyme == '[]') {
            $dataPyme = 0;
            $totalPyme = $dataPyme;
        } else {
            $totalPyme = $dataPyme[0]->total;
        }

        if ($dataYo == '[]') {
            $dataYo = 0;
            $totalYo = $dataYo;
        } else {
            $totalYo = $dataYo[0]->total;
        }

        $total = $totalPyme + $totalYo;

        Budget::where('budgets.id', '=', $id_budget)->update(['price' => $total,]);
        return $total;
    }
    public function updateAccepted(Request $request, Task $idTask)
    {
        # Valida los datos entrantes
        $request->validate([
            'manages' => ['required'],
        ]);
        # Actualiza la tarea
        $idTask->update([
            'manages' => $request->manages,
        ]);
    }
    public function updatePrice(Request $request)
    {
        $idTask = $request->id;
        $price = $request->price;

        if ($price == '') {
            $price = 0;
        }
        # Actualiza la tarea
        Task::where('tasks.id', '=', $idTask)->update(['price_customer' => $price]);
    }
    public function updateTasks(Request $request, Response $response)
    {
        $data = $request->input('data'); // asumiendo que el array bidimensional viene en la entrada 'data' de la solicitud

        foreach ($data as $registro) {

            $id = $registro[0];
            $price = $registro[1];
            $start_date = $registro[2];
            $final_date = $registro[3];

            $task = Task::find($id);
            $task->price = $price;
            $task->start_date = $start_date;
            $task->final_date = $final_date;
            $task->save();
        }

        return response()->json(['message' => 'Task updated successfully']);
    }
    public function updateSingleTask(Request $request, $id)
    {
        $price = $request->input('price'); // obtener el precio de la tarea de la solicitud
        $start_date = $request->input('start_date'); // obtener la fecha de inicio de la tarea de la solicitud
        $final_date = $request->input('final_date'); // obtener la fecha de finalización de la tarea de la solicitud

        $task = Task::find($id); // buscar la tarea en la base de datos utilizando el ID
        $task->price = $price; // actualizar el precio de la tarea
        $task->start_date = $start_date; // actualizar la fecha de inicio de la tarea
        $task->final_date = $final_date; // actualizar la fecha de finalización de la tarea
        $task->save(); // guardar los cambios en la base de datos

        return response()->json(['message' => 'Task updated successfully']);
    }
}
