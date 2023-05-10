<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmblemController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RulesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\RestoreController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ImageDeviceController;


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

//Cambiar idioma
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);
    return redirect()->back();
});

//Ruta vista dashboard cliente
Route::get('home', [UserController::class, 'dashboardUser'])->middleware(['auth', 'check_access_client']);

// login / logout
Route::get('/', [AuthController::class, 'index'])->name('index')->middleware('guest');
Route::post('/', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::get('lost-password', [AuthController::class, 'rememberPassword'])->middleware('guest')->name('rememberPassword');
Route::post('lost-password', [AuthController::class, 'rememberSend'])->name('rememberSend');
Route::get('reset-password/{token}', [AuthController::class, 'activateUser'])->middleware('guest')->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.store');
Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

//Crud Usuari
Route::middleware(['log.user'])->group(function () {
    Route::get('userList', [UserController::class, 'userList'])->name('userList');
    Route::get('userListhidden', [UserController::class, 'userListhidden'])->name('userListhidden');

    Route::get('userList/userListing/Admin', [UserController::class, 'userListing'])->name('admin'); //Usuarios ordenados por Admin(Por defecto)
    Route::get('userList/userListing/Worker', [UserController::class, 'userListingWorker'])->name('worker'); //Usuarios ordenados por Worker
    Route::get('userList/userListing/Client', [UserController::class, 'userListingClient'])->name('client'); //Usuarios ordenados por Client

    Route::get('userList/userListingHidden', [UserController::class, 'userListingHidden'])->name('userListingHidden');
    Route::get('user/{id}/unHide', [UserController::class, 'unHideUser'])->name('user.unHide')->name('unHide');


    Route::post('addUser', [UserController::class, 'addUser'])->name('addUser')->name('addUser');
    Route::post('userList/unuscribeUser', [UserController::class, 'userDown'])->name('unuscribeUser');
    Route::post('userList/editUser', [UserController::class, 'editUser'])->name('editUser')->name('editUser');
});

//Crud empresas
Route::middleware(['log.company'])->group(function () {
    Route::get('listadoEmpresas/listCompanies', [CompanyController::class, 'listCompanies'])->name('company.listCompanies');
    Route::get('listadoEmpresas/listcompanyshidden', [CompanyController::class, 'listcompanieshiddenDatos'])->name('company.listcompanyshidden');

    Route::post('listadoEmpresas/createCompany', [CompanyController::class, 'storeCompany'])->name('company.storeCompany');
    Route::post('listadoEmpresas/editCompany', [CompanyController::class, 'editCompany'])->name('company.editCompany');
    Route::post('listadoEmpresas/unsuscribeCompany', [CompanyController::class, 'unsuscribeCompany'])->name('company.unsuscribeCompany');
    Route::get('llistatEmpreses', [CompanyController::class, 'index'])->name('company.llistatEmpreses');
    Route::get('listcompanyhidden', [CompanyController::class, 'indexhidden'])->name('company.listcompanyhidden');
    Route::get('company/{id}/unHide', [CompanyController::class, 'unHideCompany'])->name('company.unHide');
    Route::get('company/listAllCompanies', [CompanyController::class, 'listAllCompanies'])->middleware('auth');
});

//Rutes per al perfil Personal i editarPerfil
Route::get('Personal_Profile', [UserController::class, 'show_user'])->name('Personal-Profile')->middleware('auth');
Route::get('PerfilPersonal_Admin', [UserController::class, 'show_user_admin'])->name('PerfilPersonal_Admin')->middleware('auth');
Route::get('user-info', [UserController::class, 'userInfo'])->name('userInfo')->middleware('auth');
Route::get('Perfil_Personal/Editar_Perfil', [UserController::class, 'editarUsuario'])->name('Editar-Perfil')->middleware('auth');
Route::post('Perfil_Personal/Editar_Perfil', [UserController::class, 'updateProfile'])->name('profile.update')->middleware('auth');
Route::post('update-profile-image', [UserController::class, 'updateProfileImage'])->name('updateProfileImage')->middleware('auth');
Route::post('delete-profile-image', [UserController::class, 'delete'])->name('deleteProfileImage')->middleware('auth');
Route::post('change-password', [UserController::class, 'changePassword'])->name('changePassword');
Route::get('contacte', [UserController::class, 'contacte'])->name('contacte')->middleware('auth');
//Admin
Route::get('PerfilPersonal_Admin/EditarPerfilAdmin', [UserController::class, 'EditarPerfilAdmin'])->name('EditarPerfilAdmin')->middleware('auth');
//Ruta para editar el usuario admin
Route::post('PerfilPersonal_Admin/Editar_Perfil/update', [UserController::class, 'updateUserAdmin'])->name('updateUserAdmin')->middleware('auth');

//Worker
Route::get('PerfilPersonal_Worker/EditarPerfilWorker', [UserController::class, 'EditarPerfilWorker'])->name('EditarPerfilWorker')->middleware('auth');
Route::post('PerfilPersonal_Worker/Editar_Perfil/update', [UserController::class, 'updateUserWorker'])->name('updateUserWorker')->middleware('auth');
Route::get('PerfilPersonal_Worker', [UserController::class, 'show_user_worker'])->name('PerfilPersonal_Worker')->middleware('auth');

//Dashboard
Route::get('taskLimit', [UserController::class, 'oneMonthTaskLimit'])->middleware('auth', 'check_access_client');
Route::get('activitiesLimit', [UserController::class, 'assignedCoursesUser'])->middleware('auth', 'check_access_client');
Route::get('graphicUserData', [UserController::class, 'graphicUserData'])->middleware('auth', 'check_access_client');
Route::get('graphicAdminData', [UserController::class, 'graphicAdminData'])->middleware('auth', 'check_access_admin');


///////

Route::get('privacy_policy', [RulesController::class, 'indexPrivacy'])->name('index.privacy')->middleware('auth');
Route::get('cookies_policy', [RulesController::class, 'indexCookies'])->name('index.cookies')->middleware('auth');
Route::get('terms', [RulesController::class, 'indexTerms'])->name('index.terms')->middleware('auth');
Route::get('edit_terms', [RulesController::class, 'indexEditTerms'])->name('edit.terms')->middleware('auth');
Route::post('edit_terms', [RulesController::class, 'storeTerms'])->name('store.terms')->middleware('auth');
Route::get('edit_terms/get', [RulesController::class, 'listTerms'])->name('list.terms')->middleware('auth');

Route::get('edit_cookies', [RulesController::class, 'indexEditCookies'])->name('edit.cookies')->middleware('auth');
Route::post('edit_cookies', [RulesController::class, 'storeCookies'])->name('store.cookies')->middleware('auth');
Route::get('edit_cookies/get', [RulesController::class, 'listCookies'])->name('list.cookies')->middleware('auth');

Route::get('edit_privacy', [RulesController::class, 'indexEditPrivacy'])->name('edit.privacy')->middleware('auth');
Route::post('edit_privacy', [RulesController::class, 'storePrivacy'])->name('store.privacy')->middleware('auth');
Route::get('edit_privacy/get', [RulesController::class, 'listPrivacy'])->name('list.privacy')->middleware('auth');

Route::get('admin', function () {
    return view('admin.dashboardAdmin');
})->middleware(['auth', 'check_access_admin']); #admin

// grup2
/* Grup de rutes per a CRUD Informe */
Route::middleware(['log.report'])->group(function () {
    Route::get('report', [ReportController::class, 'index'])->name('report.index')->middleware('auth', 'check_access_admin');
    Route::get('report/client', [ReportController::class, 'indexClient'])->name('report.user.index')->middleware('auth', 'check_access_client');
    Route::post('report', [ReportController::class, 'store'])->name('report.store')->middleware('auth', 'check_access_admin');
    Route::get('report/{id}/pdf', [ReportController::class, 'pdf'])->name('report.pdf')->middleware('auth', 'check_access_admin');
    Route::get('report/client/{id}/pdf', [ReportController::class, 'pdf'])->name('report.pdf.client')->middleware('auth', 'check_access_client');
    Route::get('report/{report}', [ReportController::class, 'show'])->name('report.show')->middleware('auth', 'check_access_admin');
    Route::get('report/client/{report}', [ReportController::class, 'showClient'])->name('report.show.client')->middleware('auth', 'check_access_client');
    Route::post('/report/{id}/delete', [ReportController::class, 'eliminar'])->name('report.eliminar')->middleware('auth', 'check_access_admin');
    Route::post('/report/{id}', [ReportController::class, 'modificar'])->name('report.modificar')->middleware('auth', 'check_access_admin');
});
//Question ROUTES
Route::middleware(['log.question'])->group(function () {
    Route::get('question', [QuestionController::class, 'index'])->name('question.index');
    Route::post('question', [QuestionController::class, 'store'])->name('question.store');
    Route::get('question/create', [QuestionController::class, 'create'])->name('question.create');
    Route::get('question/hidden', [QuestionController::class, 'hidden'])->name('question.hidden');
    Route::get('question/{id}/activate', [QuestionController::class, 'activate'])->name('question.activate');
    Route::get('question/{id}/unActivate', [QuestionController::class, 'unActivate'])->name('question.unActivate');
    Route::put('question/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::get('question/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
});

//Questionnaire ROUTES
Route::middleware(['log.questionnaire'])->group(function () {
    Route::get('questionnaire', [QuestionnaireController::class, 'index'])->name('questionnaire.index');
    Route::get('questionnaire/create', [QuestionnaireController::class, 'create'])->name('questionnaire.create');
    Route::post('questionnaire', [QuestionnaireController::class, 'store'])->name('questionnaire.store');
    Route::get('questionnaire/{questionnaire}/edit', [QuestionnaireController::class, 'edit'])->name('questionnaire.edit');
    Route::put('questionnaire/{questionnaire}', [QuestionnaireController::class, 'update'])->name('questionnaire.update');
    Route::get('questionnaire/hidden', [QuestionnaireController::class, 'hidden'])->name('questionnaire.hidden');
    Route::get('questionnaire/{id}/activate', [QuestionnaireController::class, 'activate'])->name('questionnaire.activate');
    Route::get('questionnaire/{id}/unActivate', [QuestionnaireController::class, 'unActivate'])->name('questionnaire.unActivate');
    Route::post('questionnaire/{questionnaire}/updateQuestion', [QuestionnaireController::class, 'updateQuestion'])->name('questionnaire.updateQuestion');
    Route::post('questionnaire/{questionnaire}/assignQuestion', [QuestionnaireController::class, 'assignQuestion'])->name('questionnaire.assignQuestion');
    Route::post('questionnaire/{questionnaire}/unassignQuestion', [QuestionnaireController::class, 'unassignQuestion'])->name('questionnaire.unassignQuestion');
});

//Audits ROUTES
Route::get('audit', [AuditController::class, 'index'])->name('audit.index')->middleware('auth', 'check_access_client');
Route::get('audit/{id}', [AuditController::class, 'show'])->name('audit.survey')->middleware('auth', 'check_access_client');
Route::post('audit/save/{id}', [AuditController::class, 'store'])->name('audit.store')->middleware('auth', 'check_access_client');

// final grup 2---------------------------------------- ///

//EQUIP3 ----------------------------------/////////////////
//Part Admin Crear visualitzar
Route::middleware(['log.course'])->group(function () {
    Route::get('course', [CourseController::class, 'index'])->name('course.index');
    Route::get('course/categories', [CourseController::class, 'coursecategories'])->name('course.categories');
    Route::get('categories/index_data', [CourseController::class, 'index_data_categories'])->name('course.index_data_categories');
    Route::get('allCourses', [CourseController::class, 'allCourses'])->name('allCourses');
    Route::get('allCategories', [CourseController::class, 'allCategories'])->name('allCategories');

    Route::put('category/{category}', [CourseController::class, 'updateCategory'])->name('category.update');
    Route::get('category/{id}/delete', [CourseController::class, 'CategoryDelete'])->name('category.delete');
    Route::get('rescources/admin', [ResourceController::class, 'index_admin'])->name('rescources.admin');
    Route::get('allCategories', [ResourceController::class, 'allCategories'])->name('allCategories');

    Route::get('rescources/admin-datos-TEXT', [ResourceController::class, 'index_admin_datos_text'])->name('rescources-text.admin'); //Dades recursos tipus text
    Route::get('rescources/admin-datos-URL', [ResourceController::class, 'index_admin_datos_URL'])->name('rescources-url.admin'); //Dades recursos tipus URL
    Route::get('rescources/admin-datos-FILE', [ResourceController::class, 'index_admin_datos_FILE'])->name('rescources-file.admin'); //Dades recursos tipus FILE
    Route::put('resource/text/{id}/edit', [ResourceController::class, 'updateResourceText'])->name('resourceText.update'); // Editar recurs tipus text
    Route::put('resource/{type}/{id}/edit', [ResourceController::class, 'updateResourceURL_FILE'])->name('resourceUrl.update'); //Editar recurs tipus URL o FILE





    Route::post('course', [CourseController::class, 'store2'])->name('course.store2');
    Route::post('/course', [CourseController::class, 'store2']);
    Route::post('/course/{id}', [\App\Http\Controllers\CourseController::class, 'update']);
    Route::get('/course/{id}/image', 'CourseController@getImage');
    Route::get('/course/client', [CourseController::class, 'index_client'])->name('course.client')->middleware('auth', 'check_access_client');
    Route::get('/course/client_data', [CourseController::class, 'client_data'])->name('course.client_data')->middleware('auth', 'check_access_client');

    Route::post('CreateCategory', [CourseController::class, 'createCategory'])->name('course.addCategory');
    Route::post('CreateActivity', [CourseController::class, 'createActivity'])->name('add_Activity');


    Route::post('CreateResourceText', [ResourceController::class, 'createRescourceText'])->name('addResourceText');
    Route::post('CreateResourceURL', [ResourceController::class, 'createRescourceURL'])->name('addResourceURL');
    Route::post('CreateResourceFile', [ResourceController::class, 'createRescourceFile'])->name('addResourceFile');
    Route::post('resource/{type}/{id}/delete', [ResourceController::class, 'deleteResource'])->name('resource.delete');


    Route::put('course/{course}', [CourseController::class, 'update'])->name('course.update');
    Route::put('course/update_hidden/{id}', [CourseController::class, 'update_hidden'])->name('course.update_hidden');
    Route::get('course/index_data', [CourseController::class, 'index_data'])->name('course.index_data');
    Route::get('course/users', [CourseController::class, 'users'])->name('course.users');
    Route::get('course/hidden', [CourseController::class, 'hidden'])->name('course.hidden');
    Route::get('course/hidden_data', [CourseController::class, 'hidden_data'])->name('course.hidden_data');
    Route::get('course/{id}/hide', [CourseController::class, 'hide'])->name('course.hide');
    Route::get('course/{id}/unHide', [CourseController::class, 'unHide'])->name('course.unHide');
});

Route::middleware(['log.course'])->group(function () {
    //Part Client Crear Cursos + visualitzar
    Route::get('/course/client', [CourseController::class, 'index_client'])->name('course.client')->middleware('auth', 'check_access_client');
    Route::get('course/client_data', [CourseController::class, 'client_data'])->name('course.client_data')->middleware('auth', 'check_access_client');
});

//Part Client Crear Cursos + visualitzar
Route::get('/course/client', [CourseController::class, 'index_client'])->name('course.client')->middleware('auth', 'check_access_client');
Route::get('course/client_data', [CourseController::class, 'client_data'])->name('course.client_data')->middleware('auth', 'check_access_client');
Route::get('/course/{id}/client/rescources', [ResourceController::class, 'index'])->name('course.clientRescources')->middleware('auth', 'check_access_client');
Route::get('/course/{id}/client/rescources-datos', [ResourceController::class, 'RescourceDatos'])->name('course.clientRescourcesDatos')->middleware('auth', 'check_access_client');
Route::post('/upload-image', [CourseController::class, 'uploadImage']);
Route::post('/save-image', [CourseController::class, 'saveImage']);

Route::get('emblems', [EmblemController::class, 'index'])->name('emblems.index')->middleware('auth', 'check_access_admin');
Route::get('emblems/show', [EmblemController::class, 'mostrar'])->name('emblems.show')->middleware('auth', 'check_access_admin');
Route::post('emblems/store', [EmblemController::class, 'store'])->name('emblems.store')->middleware('auth', 'check_access_admin');
Route::post('emblems/edit', [EmblemController::class, 'update'])->name('emblems.update')->middleware('auth', 'check_access_admin');
Route::get('emblems/restaurar', [EmblemController::class, 'restaurar'])->name('emblems.restaurar')->middleware('auth', 'check_access_admin');
Route::get('emblems/restaurar/mostrar', [EmblemController::class, 'restaurarMostrar'])->name('emblems.restaurar.mostrar')->middleware('auth', 'check_access_admin');
Route::get('emblems/restaurar/{emblem}', [EmblemController::class, 'restaurarhide'])->name('restaurar.hide')->middleware('auth', 'check_access_admin');
Route::get('emblems/{emblem}/eliminar', [EmblemController::class, 'eliminar'])->name('emblems.eliminar')->middleware('auth', 'check_access_admin');
// por revisar
Route::get('updateHiddenDate/{id}', [CourseController::class, 'updateHiddenDate'])->name('updateHiddenDate')->middleware('auth', 'check_access_admin');


//Part de Evaluacións
Route::get('CursosCalificar', [DeliveryController::class, 'CursosCalificar'])->name('Evaluar.Cursos')->middleware('auth', 'check_access_admin'); //Vista pagina tots els cursos
Route::get('CursosCalificar-datos', [DeliveryController::class, 'CursosCalificarDatos'])->name('Evaluar.CursosDatos')->middleware('auth', 'check_access_admin');
Route::get('CursosCalificar/{id}/activities', [DeliveryController::class, 'courseActivities'])->name('courseActivities')->middleware('auth', 'check_access_admin'); //Vista categories i activitats del curs triat
Route::get('CursosCalificar/{id}/activities-Datos', [DeliveryController::class, 'courseActivitiesDatos'])->name('courseActivitiesDatos')->middleware('auth', 'check_access_admin'); //Dades JSON categories i activitats del curs triat
Route::get('CursosCalificar/{id}/activities/{activityId}', [DeliveryController::class, 'show'])->name('ActivityDeliveries')->middleware('auth', 'check_access_admin'); //Vista alumnes amb la nota i feedback sobre la activitat triada
Route::get('CursosCalificar/{id}/activities-Datos/{activityId}', [DeliveryController::class, 'indexDatos'])->name('ActivityDeliveries.datos')->middleware('auth', 'check_access_admin'); //Dades JSON sobre els alumnes nota i feedback sobre la activitat triada
Route::post('activity/{activityId}/user/{userId}/qualify', [DeliveryController::class, 'qualify'])->name('deliveries.qualify')->middleware('auth', 'check_access_admin'); //Acció per qualificar i posar comentari a un alumne

//FIN EQUIP 3

/** ------ EQUIP 4 ------ */

Route::get('acceptacio-tasques/{id}', [TaskController::class, 'acceptacioTasques'])->name('acceptacio-tasques')->middleware('auth', 'check_access_client'); //con name() le asignas un nombre para llamarlo en los diferentes archivos

Route::get('modificar_presupuesto/{id}', function () {
    return view('Presupuestos/modificar_presupuesto/index');
})->name('modificar_presupuesto')->middleware('auth', 'check_access_client');

Route::get('gantt', function () {
    return view('Tareas/gantt/index');
})->name('gantt')->middleware('auth', 'check_access_client');

// Asignar Precios
Route::get('Asignar-Precios/{id}', [TaskController::class, 'asignarPrecios'])->name('Asignar-Precios')->middleware('auth', 'check_access_admin'); //Vista para la pagina Aceptar-Presupuestos
Route::get('mostrar-tareas/{id}', [TaskController::class, 'mostrarTareas'])->name('mostrarTareas')->middleware('auth', 'check_access_admin'); //Datos que le paso a la vista de la pagina Aceptar-Presupuestos

// Kanban
Route::get('kanban', [TaskController::class, 'kanban'])->name('vista-kanban')->middleware('auth', 'check_access_client');
Route::get('tasks', [TaskController::class, 'tasksKanban'])->name('tasks-kanban')->middleware('auth', 'check_access_client');
Route::get('tasks-admin', [TaskController::class, 'tasksKanbanAdmin'])->name('tasks-kanban-admin')->middleware('auth', 'check_access_client');
Route::put('tasks-update/{id}', [TaskController::class, 'updateState'])->name('update-state')->middleware('auth', 'check_access_client');
Route::get('kanban-admin', [TaskController::class, 'kanbanAdmin'])->name('vista-kanbanAdmin')->middleware('auth', 'check_access_admin');

// Gantt
Route::get('tasks-gantt', [TaskController::class, 'tasksGantt'])->name('tasks-gantt')->middleware('auth', 'check_access_client');

// Listar Presuspuestos
//por revisar
//Route::get('listaPresupuestos', [BudgetController::class, 'LlistatPresupuestos'])->middleware('auth', 'check_access_admin');

//per borrar
//Route::get('llista_pressupostos', [BudgetController::class, 'index'])->middleware('auth', 'check_access_admin');


Route::get('show_budgets_admin', [BudgetController::class, 'index'])->name('show_budgets_admin')->middleware('auth', 'check_access_admin');
Route::get('show_budgets_client', [BudgetController::class, 'indexClient'])->name('show_budgets_client')->middleware('auth', 'check_access_client');

//Route::get('/show_budgets_client') INPROGRESS


//por revisar
Route::get('listado_presupuestos', [BudgetController::class, 'list'])->middleware('auth', 'check_access_client');

/** ----- FINAL EQUIP 4 ------  */


/** ------ EQUIP 5 ------ */

// Restaurar
Route::get('restore', function () {
    return view('restaurar');
})->middleware('auth', 'check_access_admin');

Route::get('restaurar', [RestoreController::class, 'devices'])->name('restaurar')->middleware('auth', 'check_access_admin');
Route::post('restore/{id}', [RestoreController::class, 'restoreDevice'])->name('restaurar.post')->middleware('auth', 'check_access_admin');
Route::get('restore/{id}', [RestoreController::class, 'getIdDevice'])->name('restaurar.id')->middleware('auth', 'check_access_admin');


//Mostrar Dispositivos
Route::get('devices', [DevicesController::class, 'index'])->name('index.devices')->middleware('auth', 'check_access_admin');
Route::get('devices/list', [DevicesController::class, 'devices'])->middleware('auth', 'check_access_admin');
Route::get('devices/type_devices', [DevicesController::class, 'type_devices'])->middleware('auth', 'check_access_admin');
Route::post('devices/create', [DevicesController::class, 'create'])->middleware('auth', 'check_access_admin');
Route::post('devices/edit', [DevicesController::class, 'edit'])->middleware('auth', 'check_access_admin');
Route::post('/devices/generateqr', [DevicesController::class, 'generateqr'])->middleware('auth', 'check_access_admin');
//Route::post('/devices/delete', [DevicesController::class, 'delete']);

//Mostrar inventari
//por revisar
Route::get('inventario', function () {
    return view('inventory.inventario');
})->middleware('auth', 'check_access_client');
//por revisar
Route::get('listInventory', [InventoryController::class, 'listInventary'])->middleware('auth', 'check_access_client');

// Imatges
//por revisar
Route::get('imagenes', [ImageDeviceController::class, 'index'])->name('image.index')->middleware('auth', 'check_access_admin');
Route::post('imagenes', [ImageDeviceController::class, 'guardar'])->name('image.guardar')->middleware('auth', 'check_access_admin');
Route::get('imagenes/{id}', [ImageDeviceController::class, 'mostrar'])->name('image.mostrar')->middleware('auth', 'check_access_admin');


Route::get('/logs', [LogController::class, 'index'])->name('logs.index')->middleware('auth', 'check_access_admin');

Route::get('phpinfo', fn () => phpinfo())->middleware('auth', 'check_access_admin');
