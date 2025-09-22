<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// 商品一覧
Route::get('/products',[ProductController::class,'index'])->name('products.index');

// 登録フォーム表示
Route::get('/products/register', [ProductController::class, 'create'])->name('products.register');

// 登録処理（db保存）
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// 検索フォーム
Route::get('/products/search',[ProductController::class,'search'])->name('products.search');

// 消去
Route::delete('/products/{productId}/delete',[ProductController::class, 'destroy'])->name('products.destroy');


// 商品詳細・編集画面
Route::get('/products/{productId}', [ProductController::class, 'edit'])->name('products.edit');

// 更新
Route::put('/products/{productId}/update', [ProductController::class, 'update'])->name('products.update');
