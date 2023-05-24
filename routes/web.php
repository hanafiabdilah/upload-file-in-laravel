<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $products = Product::orderBy('created_at', 'DESC')->get();
    return view('welcome', [
        'products' => $products,
    ]);
});

Route::post('/', function(Request $request){
    $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'image' => ['required', 'mimes:jpg,png', 'max:2048']
    ]);

    $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
    $request->file('image')->storeAs('products', $imageName, 'public');

    Product::create([
        'title' => $request->title,
        'description' => $request->description,
        'image' => $imageName,
    ]);

    return back();
})->name('products.store');
