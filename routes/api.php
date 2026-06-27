// routes/api.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\KnowledgeBaseController;
use App\Http\Controllers\Api\CategoryController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Tickets
    Route::get('/tickets/stats', [TicketController::class, 'stats']);
    Route::get('/tickets/search', [TicketController::class, 'search']);
    Route::apiResource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignAgent']);
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve']);
    Route::post('/tickets/{ticket}/messages', [TicketController::class, 'addMessage']);
    Route::get('/tickets/{ticket}/ai-suggestion', [TicketController::class, 'getAISuggestion']);

    // Messages
    Route::get('/tickets/{ticket}/messages', [MessageController::class, 'index']);
    Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead']);

    // Agents (Admin only)
    Route::prefix('agents')->middleware('admin')->group(function () {
        Route::get('/', [AgentController::class, 'index']);
        Route::get('/{agent}', [AgentController::class, 'show']);
        Route::get('/{agent}/tickets', [AgentController::class, 'tickets']);
        Route::post('/{agent}/assign', [AgentController::class, 'assignTicket']);
        Route::put('/{agent}/availability', [AgentController::class, 'updateAvailability']);
    });

    // Knowledge Base
    Route::get('/knowledge/search', [KnowledgeBaseController::class, 'search']);
    Route::get('/knowledge/popular', [KnowledgeBaseController::class, 'popular']);
    Route::apiResource('knowledge', KnowledgeBaseController::class);
    Route::post('/knowledge/{article}/helpful', [KnowledgeBaseController::class, 'helpful']);
    Route::post('/knowledge/{article}/not-helpful', [KnowledgeBaseController::class, 'notHelpful']);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // Analytics (Admin only)
    Route::prefix('analytics')->middleware('admin')->group(function () {
        Route::get('/overview', [AnalyticsController::class, 'overview']);
        Route::get('/report', [AnalyticsController::class, 'generateReport']);
        Route::get('/agent-performance', [AnalyticsController::class, 'agentPerformance']);
    });
});