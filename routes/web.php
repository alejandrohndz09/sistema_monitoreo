<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => 'auth'], function () {
	// Rutas para Super Admin (rol 0)
	Route::group(['middleware' => ['role:0']], function () {
		Route::get('/empresas/{idEmpresa}/obtener-usuarios', 'App\Http\Controllers\UsuarioController@getUsuarios');
		Route::get('/empresas/{idEmpresa}/usuarios/baja/{id}', 'App\Http\Controllers\UsuarioController@baja');
		Route::get('/empresas/{idEmpresa}/usuarios/alta/{id}', 'App\Http\Controllers\UsuarioController@alta');
		Route::resource('/empresas/{idEmpresa}/usuarios', 'App\Http\Controllers\UsuarioController');

		Route::get('/dashboard-sadmin', 'SuperAdminController@index');
		Route::get('/enviar-credenciales/{id}', 'App\Http\Controllers\EmpresaController@enviarCredenciales');
		Route::get('/obtener-empresas', 'App\Http\Controllers\EmpresaController@getEmpresas');
		Route::get('/empresas/baja/{id}', 'App\Http\Controllers\EmpresaController@baja');
		Route::get('/empresas/alta/{id}', 'App\Http\Controllers\EmpresaController@alta');
		Route::resource('/empresas', 'App\Http\Controllers\EmpresaController');
	});
	

	Route::group(['middleware' => ['role:1,2']], function () {
		Route::get('/nueva-evaluacion', 'App\Http\Controllers\EvaluacionController@create');
		Route::get('/evaluaciones/{id}', 'App\Http\Controllers\EvaluacionController@show');		
		Route::resource('/evaluaciones', 'App\Http\Controllers\EvaluacionController');

	});
	
	
	Route::group(['middleware' => ['role:1']], function () {
		//Mis Usuarios
		Route::get('/mi-empresa/{idEmpresa}/obtener-usuarios', 'App\Http\Controllers\UsuarioController@getUsuarios');
		Route::get('/mi-empresa/{idEmpresa}/usuarios/baja/{id}', 'App\Http\Controllers\UsuarioController@baja');
		Route::get('/mi-empresa/{idEmpresa}/usuarios/alta/{id}', 'App\Http\Controllers\UsuarioController@alta');
		Route::resource('/mi-empresa/{idEmpresa}/usuarios', 'App\Http\Controllers\UsuarioController');
		//Mi Empresa
		Route::get('/mi-empresa/{idEmpresa}', 'App\Http\Controllers\EmpresaController@show');

		Route::get('/evaluaciones/obtener-evaluaciones', 'App\Http\Controllers\EvaluacionController@getEvaluaciones');
		Route::get('/evaluaciones/baja/{id}', 'App\Http\Controllers\EvaluacionController@baja');
		Route::get('/evaluaciones/alta/{id}', 'App\Http\Controllers\EvaluacionController@alta');
	});

	

	
	Route::get('/', [HomeController::class, 'home']);
	Route::get('inicio', function () {
		return view('dashboard');
	})->name('inicio');

	Route::post('/logout', 'App\Http\Controllers\LoginController@logout');
});

Route::get('login', function () {
	return view('usuarios.login');
})->name('login')->middleware('guest');
Route::post('/recuperarClaveMail', 'App\Http\Controllers\LoginController@recuperarClaveMail')->middleware('guest');
Route::get('/codigo-seguridad/{token}', 'App\Http\Controllers\LoginController@vistaToken')->middleware('guest');
Route::post('/verificar-token', 'App\Http\Controllers\LoginController@verificarToken')->middleware('guest');
Route::get('/cambiar-clave/{token}/{temporal?}', 'App\Http\Controllers\LoginController@vistaCambiarClave')->middleware('guest');
Route::post('/verificar-cambio', 'App\Http\Controllers\LoginController@cambiarClave')->middleware('guest');

Route::post('/login', 'App\Http\Controllers\LoginController@login');
