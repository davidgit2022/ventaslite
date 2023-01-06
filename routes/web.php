<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\BlankComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Pos\PosComponent;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\Products\ProductsComponent;
use App\Http\Livewire\Category\CategoriesComponent;
use App\Http\Livewire\Denominations\DenominationsComponent;





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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/categories', CategoriesComponent::class)->name('categories');
Route::get('/products', ProductsComponent::class)->name('products');
Route::get('/denominations', DenominationsComponent::class)->name('denominations');
Route::get('/pos', PosComponent::class)->name('pos');

