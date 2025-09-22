<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminDashboardController; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;


// Home and Public Pages
Route::get('/', function () {return view('index');})->name('home');
Route::get('/eligibility', function () {return view('eligibility'); })->name('eligibility');
Route::get('/guidelines', function () {return view('guidelines');})->name('guidelines'); 
Route::get('/order', function () {return view('order');})->name('order');

// Application Form Routes
Route::get('/application', [ApplicationController::class, 'showForm'])->name('application.form');
Route::post('/application', [ApplicationController::class, 'store'])->name('application.submit');
Route::post('/application/store', [ApplicationController::class, 'store'])->name('application.store');
Route::get('/success', function () {return view('success');})->name('success');
// Route::get('/success/{id}', [ApplicationController::class, 'success'])->name('success');


// The route for the success page
Route::get('/success/{id}', [ApplicationController::class, 'success'])->name('application.success');


// This route for viewing applications
Route::get('/admin/applications', [AdminDashboardController::class, 'showApplications'])->name('admin.applications.index');
Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');


// Login 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// dashboards
Route::get('/admin/dashboard', function () {return "Welcome Admin Dashboard";})->name('admin.dashboard')->middleware('auth');

Route::get('/user/dashboard', function () {return "Welcome User Dashboard";})->name('user.dashboard')->middleware('auth');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

// Login route already exists
Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::post('register', [RegisterController::class, 'register'])->name('register.store');


Route::get('/index', function () {return view('index');})->name('index');
// Route::get('/index', [HomeController::class, 'index'])->name('index')->middleware(['auth', 'role:user']);
Route::get('/admin', [HomeController::class, 'admin'])->middleware(['auth', 'role:admin']);
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'role:admin,user']);
Route::get('/index', [HomeController::class, 'index'])->middleware(['auth', 'role:user'])->name('index');




Route::middleware(['auth', 'role:admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', 'role:user'])->get('/index', [HomeController::class, 'index'])->name('index');
Route::middleware(['auth', 'role:user'])->group(function () {Route::get('/index', [HomeController::class, 'index'])->name('index');});
Route::middleware(['auth', 'role:admin'])->group(function () {Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');});
// Route::resource('roles', RoleController::class);
Route::get('/roles', [RoleController::class, 'index']);












