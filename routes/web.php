<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

// ==================== ПУБЛИЧНЫЕ МАРШРУТЫ ====================

// Главная
Route::get('/', [ProductController::class, 'new'])->name('home');

// Товары
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/sale', [ProductController::class, 'sale'])->name('sale');
    Route::get('/new', [ProductController::class, 'new'])->name('new');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/filters', [ProductController::class, 'filters'])->name('filters');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
});

// Категории
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
});

// ==================== ГОСТИ (НЕ АВТОРИЗОВАННЫЕ) ====================
Route::middleware('guest')->group(function () {
    // Регистрация
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    
    // Вход
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// ==================== АВТОРИЗОВАННЫЕ ПОЛЬЗОВАТЕЛИ ====================
Route::middleware('auth')->group(function () {
    // Выход
    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout');
    
    // Корзина
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/apply-bonus', [CartController::class, 'applyBonus'])->name('apply-bonus');
        Route::post('/remove-bonus', [CartController::class, 'removeBonus'])->name('remove-bonus');
    });
    
    // Заказы
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/reorder', [OrderController::class, 'reorder'])->name('reorder');
        Route::post('/{id}/pay', [OrderController::class, 'pay'])->name('pay');
    });
    
    // Отзывы (раскомментировано)
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/product/{productId}', [ReviewController::class, 'store'])->name('store');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });
});

// ==================== ДОПОЛНИТЕЛЬНО ====================

// Страница "О нас" (пример)
Route::view('/about', 'about')->name('about');

// Страница контактов (пример)
Route::view('/contacts', 'contacts')->name('contacts');

// Если нужен файл auth.php - создайте его или удалите строку ниже
// require __DIR__.'/auth.php';