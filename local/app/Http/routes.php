<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::Resource('/','HomeController@index');
/*Route::get('/', function()
{
	return View::make('home');
});*/

Route::group(['middleware' => 'App\Http\Middleware\Authenticate'], function()
{
//    Route::resource('/', 'HomeController@index');

Route::Resource('/config/getTipoPagos','ConfigController@getTipoPagos');
Route::Resource('/config/getRoles','ConfigController@getRoles');
Route::Resource('/config','ConfigController@index');

Route::post('/config/create', 'ConfigController@create');


Route::Resource('/usuarios','UsuariosController@index');
Route::Resource('/usuarios/destroy','UsuariosController@destroy');
Route::Resource('/usuarios/create','UsuariosController@create');
Route::Resource('/usuarios/buscar','UsuariosController@buscar');
Route::Resource('/usuarios/show','UsuariosController@show');
Route::Resource('/usuarios/edit','UsuariosController@edit');
//Route::Resource('/config/create','ConfigController@create');
Route::Resource('/roles','RolesController@index');
Route::Resource('/roles/destroy','RolesController@destroy');
Route::Resource('/roles/create','RolesController@create');
Route::Resource('/roles/show','RolesController@show');
Route::Resource('/roles/edit','RolesController@edit');
Route::Resource('/roles/permisos','RolesController@permisos');

Route::Resource('/clientes/getComboClientes','ClientesController@getComboClientes');
Route::Resource('/clientes','ClientesController@index');
Route::Resource('/clientes/destroy','ClientesController@destroy');
Route::Resource('/clientes/create','ClientesController@create');
Route::Resource('/clientes/show','ClientesController@show');
Route::Resource('/clientes/edit','ClientesController@edit');
Route::Resource('/clientes/buscar','ClientesController@buscar');


Route::Resource('/planes/getComboPlanes','PlanesController@getComboPlanes');
Route::Resource('/planes','PlanesController@index');;
Route::Resource('/planes/destroy','PlanesController@destroy');
Route::Resource('/planes/create','PlanesController@create');
Route::Resource('/planes/show','PlanesController@show');
Route::Resource('/planes/buscar','PlanesController@buscar');


Route::Resource('/contrato','ContratosController@nuevo');
Route::Resource('/intenciones','ContratosController@index');
Route::Resource('/contratos/create','ContratosController@create');
Route::Resource('/contratos/editar','ContratosController@editar');
Route::Resource('/contratos/getPlanesById','ContratosController@getPlanesById');
Route::Resource('/contratos/imprimir','ContratosController@imprimir');
Route::Resource('/contratos/recibo','ContratosController@recibo');
Route::Resource('/contratos/enganche','ContratosController@enganche');
Route::Resource('/contratos/buscar','ContratosController@buscar');
Route::Resource('/contratos/contrato','ContratosController@contrato');
Route::Resource('/contratos/tabla','ContratosController@tabla');
Route::Resource('/contratos/cancelar','ContratosController@cancelar');
Route::Resource('/contratos/destroy','ContratosController@destroy');

Route::Resource('/edos','PagosController@index');
Route::Resource('/pagos','PagosController@pagos');
Route::Resource('/pagos/buscar','PagosController@buscar');
Route::Resource('/pagos/create','PagosController@create');
Route::Resource('/pagos/recibo_anticipado','PagosController@recibo_anticipado');
Route::Resource('/pagos/recibo_esp','PagosController@recibo_esp');
Route::Resource('/pagos/recibo2','PagosController@recibo2');
Route::Resource('/pagos/recibo','PagosController@recibo');
Route::Resource('/pagos/pagoAnticipada','PagosController@pagoAnticipada');
Route::Resource('/pagos/actualizar','PagosController@actualizar');
Route::Resource('/pagos/borrar','PagosController@borrar');
Route::Resource('/pagos/recibo_contado','PagosController@recibo_contado');

Route::Resource('/cobranza/create','PagosController@crear_seguimiento');
Route::Resource('/cobranza/historico','PagosController@historico');


Route::Resource('/reporte','ReportController@index');
Route::Resource('/corte','ReportController@corte');
Route::Resource('/reporte/buscar','ReportController@buscar');
Route::Resource('/reporte/buscar_corte','ReportController@buscar_corte');

Route::Resource('/chart','ReportController@chart');
Route::Resource('/reporte/buscar_chart','ReportController@buscar_chart');

Route::Resource('/convenio','ConvenioController@index');
Route::Resource('/convenio/buscar','ConvenioController@buscar');
Route::Resource('/convenio/create','ConvenioController@create');
Route::Resource('/convenio/getConvenios','ConvenioController@getConvenios');
Route::Resource('/convenio/show','ConvenioController@show');
Route::Resource('/convenio/imprimir','ConvenioController@imprimir');

});



/* Show the 'Login' page */
Route::get('login', function()
{
    
    return View::make('/login');

});

/* Logout the user, and redirect to the 'Login' screen */
Route::get('logout', function()
{
    Auth::logout();
    Session::flush();
    return Redirect::to('/login');
    //return View::make('/login');
});

/* Process the login request */
Route::post('login', function()
{

    $userdata = array(
            'usuario' => Input::get('email'),
            'password' => Input::get('password'),
						'estatus' => 1
        );

    
    $isAuth= Auth::attempt($userdata);

    if($isAuth)
    {
        return Redirect::to('/');

    }
    else
    {
        
        Session::flash('alert-warning', 'Revise usuario o contraseña');
        return View::make('/login');
        
    }
});