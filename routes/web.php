<?php 

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ChatWidgetController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Public widget routes
Route::get('/widget/{uuid}', [ChatWidgetController::class, 'show'])->name('widget.show');
Route::get('/widget/{uuid}/bubble', [ChatWidgetController::class, 'showBubble'])->name('widget.bubble');
Route::post('/widget/{uuid}/send', [ChatWidgetController::class, 'sendMessage'])->name('widget.send');
Route::get('/widget/{uuid}/info', [ChatWidgetController::class, 'info'])->name('widget.info');

// Authentication routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    })->name('dashboard');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chatbot routes - specific routes first, then parameterized routes
    Route::get('/chatbots', [ChatbotController::class, 'index'])->name('chatbots.index');
    Route::get('/chatbots/create', [ChatbotController::class, 'create'])->name('chatbots.create');
    Route::post('/chatbots/store', [ChatbotController::class, 'store'])->name('chatbots.store');
    Route::get('/chatbots/{chatbot}', [ChatbotController::class, 'show'])->name('chatbots.show');
    Route::get('/chatbots/{chatbot}/edit', [ChatbotController::class, 'edit'])->name('chatbots.edit');
    Route::patch('/chatbots/{chatbot}', [ChatbotController::class, 'update'])->name('chatbots.update');
    Route::get('/chatbots/{chatbot}/analytics', [ChatbotController::class, 'analytics'])->name('chatbots.analytics');
    Route::delete('/chatbots/{chatbot}', [ChatbotController::class, 'destroy'])->name('chatbots.destroy');

    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});

// Root route
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'widgetBubbleUrl' => route('widget.bubble', '8dc54a8d-8843-4942-a20b-96b0c4e89bfb'),
    ]);
});
