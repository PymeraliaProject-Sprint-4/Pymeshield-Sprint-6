<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        //pagina principal de informes mostra llistat informes (solo sin completar)
        $reports = Report::whereNull('hidden')
            ->orderBy('status', 'asc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $questionnaires = Questionnaire::all();
        $users = User::all();

        return view('report.index', compact('reports', 'questionnaires', 'users'));
    }

    public function indexClient()
    {
        //pagina principal de informes mostra llistat informes (solo sin completar)
        $userId = Auth::id();
        $reports = Report::whereNull('hidden')
            ->where('user_id', $userId)
            ->orderBy('status', 'asc')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $questionnaire = Questionnaire::all();
        $users = User::all();

        return view('report.index_user', compact('reports', 'questionnaire', 'users'));
    }
    public function show($id)
    {
        //pagina que mostra els detalls de informes
        $report = DB::table('reports')
            ->select('answers.id', 'answers.name as answers', 'answers.recommendation as recommendation', 'questions.name as questions', 'type_measures.name as type_measures', 'risks.name as risks', 'probabilities.name as probabilities', 'interventions.name as interventions', 'impacts.name as impacts')
            ->join('answer_report', 'answer_report.report_id', '=', 'reports.id')
            ->join('answers', 'answer_report.answer_id', '=', 'answers.id')
            ->join('impacts', 'impacts.id', '=', 'answers.impact_id')
            ->join('interventions', 'interventions.id', '=', 'answers.intervention_id')
            ->join('probabilities', 'probabilities.id', '=', 'answers.probability_id')
            ->join('questions', 'questions.id', '=', 'answers.question_id')
            ->join('risks', 'risks.id', '=', 'answers.risk_id')
            ->join('type_measures', 'type_measures.id', '=', 'answers.type_measure_id')
            ->where('reports.id', '=', $id)
            ->get();
        return view('report.show', compact('report', 'id'));
    }
    public function showClient($id)
    {
        //pagina que mostra els detalls de informes
        $userId = Auth::id();
        $report = DB::table('reports')
            ->select('answers.id', 'answers.name as answers', 'answers.recommendation as recommendation', 'questions.name as questions', 'type_measures.name as type_measures', 'risks.name as risks', 'probabilities.name as probabilities', 'interventions.name as interventions', 'impacts.name as impacts')
            ->join('answer_report', 'answer_report.report_id', '=', 'reports.id')
            ->join('answers', 'answer_report.answer_id', '=', 'answers.id')
            ->join('impacts', 'impacts.id', '=', 'answers.impact_id')
            ->join('interventions', 'interventions.id', '=', 'answers.intervention_id')
            ->join('probabilities', 'probabilities.id', '=', 'answers.probability_id')
            ->join('questions', 'questions.id', '=', 'answers.question_id')
            ->join('risks', 'risks.id', '=', 'answers.risk_id')
            ->join('type_measures', 'type_measures.id', '=', 'answers.type_measure_id')
            ->where('reports.id', '=', $id)
            ->where('user_id', $userId)
            ->get();
        return view('report.show_client', compact('report', 'id'));
    }
    public function pdf($id)
    {
        $report = Report::with(['answers', 'answers.impact', 'answers.intervention', 'answers.probability', 'answers.question', 'answers.risk', 'answers.typeMeasure'])
            ->findOrFail($id);
        // crea carpeta fonts si no existe
        if (!File::exists(('fonts'))) {
            File::makeDirectory(storage_path('fonts'));
        }
        $today = Carbon::now()->format('d/m/Y');
        $pdf = PDF::loadView('report.pdf', compact('report', 'today'))->setPaper('legal', 'landscape');
        return $pdf->stream();
    }

    public function store(Request $request)
    {
        // crear un report (desde la vista auditorías)
        $report = new Report();
        // validar datos
        $validated = $request->validate([
            'reportName' => 'required|string|max:50',
            'selectedQuestionnaire' => 'required|integer',
            'selectedUser' => 'required|integer'
        ]);
        $reportName = $validated['reportName'];
        $selectedQuestionnaire = $validated['selectedQuestionnaire'];
        $selectedUser = $validated['selectedUser'];
        $report->name = $reportName;
        $report->questionnaire_id = $selectedQuestionnaire;
        $report->user_id = $selectedUser;
        $report->date = date('Y-m-d');
        $report->status = "pending";
        $report->save();
        return response()->json([
            'redirect' => route('questionnaire.index')
        ]);
    }
    function indexmobil()
    {
        if (auth()->user()->type == 'client') {
            $data = DB::table('reports')
                ->select('id', 'name', 'status')
                ->where('user_id', '=', auth()->user()->id)
                ->get();

            return response()->json($data);
        } else {
            $data = DB::table('reports')
                ->select('id', 'name', 'status')
                ->get();

            return response()->json($data);
        }
    }
    public function modificar(Request $request, $id)
    {
        $report = Report::find($id);
        $report->name = $request->input('name');
        $report->status = $request->input('status');

        $report->save();

        return back();
    }

    public function eliminar($id)
    {
        $report = Report::find($id);
        $dateNow = date('Y-m-d');
        $report->hidden = $dateNow;

        $report->save();

        return back();
    }
}
