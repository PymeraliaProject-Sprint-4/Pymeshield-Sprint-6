<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmblemController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RulesController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LanguageController;
use App\Http\Middleware\LocaleMiddleware;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AnswerControler;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RestoreController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ImageDeviceController;
use App\Models\Device;
//

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Ruta que se utiliza para cambiar el idioma de la aplicación i guardar la selección en la sesión del usuario
 *
 * setLocale() establece el idioma activo de la aplicación para el idioma seleccionado actual
 * La segunda línea guarda el idioma activo en la sesión de la aplicación utilizando el método 'put' de la instancia de sesión (session()),
 * de esta manera, el idioma activo persistirá en todas las solicitudes posteriores.
 *
 * @param $locale representa que el usurio quiere establecer en la aplicación
 * @return back devuelve al usuario a la página donde se encuentraba antes de cambiar el idioma de la aplicación
 */
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

//Ruta vista dashboard cliente
Route::get('/home', [UserController::class, 'dashboardUser'])->middleware(['auth', 'check_access_client']);


Route::get('/Perfil_Personal/Editar_Perfil', [UserController::class, 'editarUsuario'])->name('Editar-Perfil')->middleware('auth');

Route::patch('/Perfil_Personal/Editar_Perfil', [UserController::class, 'updateProfile'])->name('profile.update');





Route::get('/request', function () {
    return view('http.show');
})->middleware(['auth', 'check_access_client']);

Route::get('/llistatEmpreses', [CompanyController::class, 'index'])->middleware('auth');

Route::get('/', [AuthController::class, 'index'])->name('index')->middleware('guest');

Route::get('/lost-password', [AuthController::class, 'rememberPassword'])->middleware('guest')->name('rememberPassword');

Route::post('/lost-password', [AuthController::class, 'rememberSend'])->name('rememberSend');

//Crud Usuari
Route::get('/userList', [UserController::class, 'userList'])->name('userList');
Route::get('userList/userListing', [UserController::class, 'userListing'])->middleware('auth');
Route::get('userList/userListingHidden', [UserController::class, 'userListing'])->middleware('auth');

Route::post('/addUser', [UserController::class, 'addUser'])->name('addUser');
Route::post('/userDown', [UserController:: class, 'userDown']);
Route::post('/editUser', [UserController:: class, 'editUser']);


Route::get('/reset-password/{token}', [AuthController::class, 'activateUser'])->middleware('guest')->name('password.reset');

Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.store');

Route::post('/', [AuthController::class, 'login'])->middleware('guest')->name('login'); #test

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('listadoEmpresas/listCompanies', [CompanyController::class, 'listCompanies'])->middleware('auth');
Route::post('listadoEmpresas/createCompany', [CompanyController:: class, 'storeCompany'])->middleware('auth');
Route::post('listadoEmpresas/editCompany', [CompanyController:: class, 'editCompany'])->middleware('auth');
Route::post('listadoEmpresas/unsuscribeCompany', [CompanyController:: class, 'unsuscribeCompany'])->middleware('auth');

//Rutes per al perfil Personal i editarPerfil
Route::get('/Personal_Profile', [UserController::class, 'show_user'])->name('Personal-Profile')->middleware('auth');
Route::get('/PerfilPersonal_Admin', [UserController::class, 'show_user_admin'])->name('PerfilPersonal_Admin')->middleware('auth');
Route::get('/user-info', [UserController::class, 'userInfo'])->name('userInfo')->middleware('auth');
Route::get('/Perfil_Personal/Editar_Perfil', [UserController::class, 'editarUsuario'])->name('Editar-Perfil')->middleware('auth');
Route::post('/Perfil_Personal/Editar_Perfil', [UserController::class, 'updateProfile'])->name('profile.update');
Route::post('/update-profile-image', [UserController::class, 'updateProfileImage'])->name('updateProfileImage');
Route::post('/delete-profile-image', [UserController::class,'delete'])->name('deleteProfileImage');
Route::post('/change-password', [UserController::class,'changePassword'])->name('changePassword');
Route::get('/contacte', [UserController::class, 'contacte'])->name('contacte')->middleware('auth');
//Admin
Route::get('/PerfilPersonal_Admin/EditarPerfilAdmin', [UserController::class, 'EditarPerfilAdmin'])->name('EditarPerfilAdmin')->middleware('auth');
//Ruta para editar el usuario admin
Route::post('/PerfilPersonal_Admin/Editar_Perfil/update', [UserController::class, 'updateUserAdmin'])->name('updateUserAdmin')->middleware('auth');

//Worker
Route::get('/PerfilPersonal_Worker/EditarPerfilWorker', [UserController::class, 'EditarPerfilWorker'])->name('EditarPerfilWorker')->middleware('auth');
Route::post('/PerfilPersonal_Worker/Editar_Perfil/update', [UserController::class, 'updateUserWorker'])->name('updateUserWorker')->middleware('auth');
Route::get('/PerfilPersonal_Worker', [UserController::class, 'show_user_worker'])->name('PerfilPersonal_Worker')->middleware('auth');



///////

Route::get('/privacy_policy', [RulesController::class, 'indexPrivacy'])->name('index.privacy')->middleware('auth');
Route::get('/cookies_policy', [RulesController::class, 'indexCookies'])->name('index.cookies')->middleware('auth');
Route::get('/terms', [RulesController::class, 'indexTerms'])->name('index.terms')->middleware('auth');
Route::get('/edit_terms', [RulesController::class, 'indexEditTerms'])->name('edit.terms')->middleware('auth');
Route::post('/edit_terms', [RulesController::class, 'storeTerms'])->name('store.terms')->middleware('auth');
Route::get('/edit_terms/get', [RulesController::class, 'listTerms'])->name('list.terms')->middleware('auth');

Route::get('/edit_cookies', [RulesController::class, 'indexEditCookies'])->name('edit.cookies')->middleware('auth');
Route::post('/edit_cookies', [RulesController::class, 'storeCookies'])->name('store.cookies')->middleware('auth');
Route::get('/edit_cookies/get', [RulesController::class, 'listCookies'])->name('list.cookies')->middleware('auth');

Route::get('/edit_privacy', [RulesController::class, 'indexEditPrivacy'])->name('edit.privacy')->middleware('auth');
Route::post('/edit_privacy', [RulesController::class, 'storePrivacy'])->name('store.privacy')->middleware('auth');
Route::get('/edit_privacy/get', [RulesController::class, 'listPrivacy'])->name('list.privacy')->middleware('auth');

Route::get('/admin', function () {
    return view('admin.dashboardAdmin');
})->middleware(['auth', 'check_access_admin']); #admin

// grup2
/* Grup de rutes per a CRUD Informe */
Route::resource('report', ReportController::class);
Route::get('/report/{id}/pdf', [ReportController::class, 'pdf'])->name('report.pdf');


/* Grup de rutes per a CRUD Respostes */
Route::resource('answer', QuestionController::class);

//Question ROUTES
Route::resource('question', QuestionController::class)->except(['show']);
Route::get('/question/{id}/activate', [QuestionController::class, 'activate'])->name('question.activate');
Route::get('/question/{id}/unActivate', [QuestionController::class, 'unActivate'])->name('question.unActivate');
Route::get('question/hidden', [QuestionController::class, 'hidden'])->name('question.hidden');

//Questionnaire ROUTES
Route::resource('questionnaire', QuestionnaireController::class)->except(['show']);
Route::get('questionnaire/hidden', [QuestionnaireController::class, 'hidden'])->name('questionnaire.hidden');
Route::get('questionnaire/{id}/activate', [QuestionnaireController::class, 'activate'])->name('questionnaire.activate');
Route::get('questionnaire/{id}/unActivate', [QuestionnaireController::class, 'unActivate'])->name('questionnaire.unActivate');
Route::post('questionnaire/{questionnaire}/updateQuestion', [QuestionnaireController::class, 'updateQuestion'])->name('questionnaire.updateQuestion');
Route::post('questionnaire/{questionnaire}/assignQuestion', [QuestionnaireController::class, 'assignQuestion'])->name('questionnaire.assignQuestion');
Route::post('questionnaire/{questionnaire}/unassignQuestion', [QuestionnaireController::class, 'unassignQuestion'])->name('questionnaire.unassignQuestion');

//Audits ROUTES
Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
Route::get('/audit/{id}', [AuditController::class, 'show'])->name('audit.survey');
Route::post('/audit/save/{id}', [AuditController::class, 'store'])->name('audit.store');

// final grup 2---------------------------------------- ///

//EQUIP3 ----------------------------------/////////////////
//Part Admin Crear visualitzar
Route::resource('course', CourseController::class)->except('show');
Route::put('course/update_hidden/{id}', [CourseController::class, 'update_hidden'])->name('course.update_hidden');
Route::get('course/index_data', [CourseController::class, 'index_data'])->name('course.index_data');
Route::get('course/users', [CourseController::class, 'users'])->name('course.users');
Route::get('course/hidden', [CourseController::class, 'hidden'])->name('course.hidden');
Route::get('course/hidden_data', [CourseController::class, 'hidden_data'])->name('course.hidden_data');

Route::get('course/{id}/hide', [CourseController::class, 'hide'])->name('course.hide');
Route::get('course/{id}/unHide', [CourseController::class, 'unHide'])->name('course.unHide');

//Part Client Crear Cursos + visualitzar
Route::get('/course/client', [CourseController::class, 'index_client'])->name('course.client');
Route::get('course/client_data', [CourseController::class, 'client_data'])->name('course.client_data');



Route::get('/emblems', [EmblemController::class, 'index'])->name('emblems.index');
Route::get('/emblems/create', [EmblemController::class, 'create'])->name('emblems.create');
Route::post('/emblems/store', [EmblemController::class, 'store'])->name('emblems.store');
Route::get('/emblems/{emblem}/edit', [EmblemController::class, 'edit'])->name('emblems.edit');
Route::post('/emblems/{emblem}', [EmblemController::class, 'update'])->name('emblems.update');
Route::get('/emblems/restaurar', [EmblemController::class, 'restaurar'])->name('emblems.restaurar');
Route::get('emblems/restaurar/{emblem}', [EmblemController::class, 'restaurarhide'])->name('restaurar.hide');
Route::get('emblems/eliminar/{emblem}', [EmblemController::class, 'eliminar'])->name('emblems.eliminar');

Route::get('/updateHiddenDate/{id}', [CourseController::class, 'updateHiddenDate'])->name('updateHiddenDate');


Route::get('/recursos', [ResourceController::class, 'index'])->name('recourse.index');


//Part de Evaluacións
Route::get('/CursosCalificar', [DeliveryController::class, 'CursosCalificar'])->name('Evaluar.Cursoss'); //Vista pagina tots els cursos
Route::get('/CursosCalificar/{id}/activities', [DeliveryController::class, 'courseActivities'])->name('courseActivities'); //Vista categories i activitats del curs triat
Route::get('/activitiesProva/{id}/activities-Datos', [DeliveryController::class, 'courseActivitiesDatos'])->name('courseActivitiesDatos'); //Dades JSON categories i activitats del curs triat
Route::get('/CursosCalificar/{id}/activities/{activityId}', [DeliveryController::class, 'show'])->name('ActivityDeliveries'); //Vista alumnes amb la nota i feedback sobre la activitat triada
Route::get('/CursosCalificar/{id}/activities-Datos/{activityId}', [DeliveryController::class, 'indexDatos'])->name('ActivityDeliveries.datos'); //Dades JSON sobre els alumnes nota i feedback sobre la activitat triada
Route::post('/activity/{activityId}/user/{userId}/qualify', [DeliveryController::class, 'qualify'])->name('deliveries.qualify'); //Acció per qualificar i posar comentari a un alumne
//FIN EQUIP 3



/** ------ EQUIP 4 ------ */

Route::get('/acceptacio-tasques', [TaskController::class, 'acceptacioTasques'])->name('acceptacio-tasques'); //con name() le asignas un nombre para llamarlo en los diferentes archivos

Route::get('/my-tasks', function () {
    return view('Presupuestos/modificar_presupuesto/index');
})->name('my-tasks');

Route::get('/modificar_presupuesto', function () {
    return view('Presupuestos/modificar_presupuesto/index');
})->name('modificar_presupuesto');

Route::get('/gantt', function () {
    return view('Tareas/gantt/index');
})->name('gantt');

Route::get('select', function() {
    return view('select');
});
Route::get('radio', function() {
    return view('radio');
});

// Asignar Precios
Route::get('/Asignar-Precios/{id}', [TaskController::class, 'asignarPrecios'])->name('Asignar-Precios'); //Vista para la pagina Aceptar-Presupuestos
Route::get('/mostrar-tareas/{id}', [TaskController::class, 'mostrarTareas'])->name('mostrarTareas'); //Datos que le paso a la vista de la pagina Aceptar-Presupuestos

// Kanban
Route::get('/kanban', [TaskController::class, 'kanban'])->name('vista-kanban');
Route::get('/tasks', [TaskController::class, 'tasksKanban'])->name('tasks-kanban');
Route::put('/tasks-update/{id}', [TaskController::class, 'updateState'])->name('update-state');

// Gantt
Route::get('/tasks-gantt', [TaskController::class, 'tasksGantt'])->name('tasks-gantt');

// Listar Presuspuestos
Route::get('/listaPresupuestos', [BudgetController::class, 'LlistatPresupuestos']);
Route::get('/llista_pressupostos',[BudgetController::class, 'index']);
Route::get('/listado_presupuestos',[BudgetController::class, 'list']);

/** ----- FINAL EQUIP 4 ------  */


/** ------ EQUIP 5 ------ */

Route::get('/index', HomeController::class);

// Restaurar
Route::get('/restore', function(){return view('restaurar');});
Route::get('/restaurar',[RestoreController::class, 'devices'])->name('restaurar');
Route::post('/restore/{id}', [RestoreController::class, 'restoreDevice'])->name('restaurar');
Route::get('/restore/{id}', [RestoreController::class, 'getIdDevice'])->name('restaurar');

//Mapa
Route::get('/map', function(){return view('mapa');});


//Mostrar Dispositivos
Route::get('/devices', function(){ return view('listdevices');});
Route::get('/devices/list', [DevicesController::class, 'devices']);
Route::get('/devices/type_devices', [DevicesController::class, 'type_devices']);
Route::post('/devices/create', [DevicesController::class, 'create']);
Route::post('/devices/edit', [DevicesController::class, 'edit']);
//Route::post('/devices/delete', [DevicesController::class, 'delete']);

//Mostrar inventari
Route::get('/inventario', function(){ return view('inventario');});
Route::get('/mapa', function(){ return view('mapa');});

Route::get('/listInventory', [InventoryController::class, 'listInventary']);

// Imatges
Route::get('/imagenes', [ImageDeviceController::class, 'index'])->name('image.index');
Route::post('/imagenes', [ImageDeviceController::class, 'guardar'])->name('image.guardar');
Route::get('/imagenes/{id}', [ImageDeviceController::class, 'mostrar'])->name('image.mostrar');
