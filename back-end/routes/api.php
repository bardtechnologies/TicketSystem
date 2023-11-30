<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Ticket Routes
use App\Http\Controllers\TicketController;

Route::controller(TicketController::class)->middleware(['auth'])->group(function () {
    Route::post('/ticket-data', 'ticketData');
    Route::get('/ticket-count', 'tableLength');
});

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketStatusController;
use App\Http\Controllers\TicketPriorityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueTypeController;
use App\Http\Controllers\IssueStatusController;
use App\Http\Controllers\IssuePriorityController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\SupportArticleController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TemplateTypeController;
use App\Http\Controllers\TemplateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('tickets' , TicketController::class)
->middleware('auth');
Route::apiResource('clients' , ClientController::class)
->middleware('auth');
Route::apiResource('products', ProductController::class)
->middleware('auth');
Route::apiResource('ticket-statuses', TicketStatusController::class)
->middleware('auth');
Route::apiResource('ticket-priorities', TicketPriorityController::class)
->middleware('auth');
Route::apiResource('issue-types', IssueTypeController::class)
->middleware('auth');
Route::apiResource('issue-statuses', IssueStatusController::class)
->middleware('auth');
Route::apiResource('issue-priorities', IssuePriorityController::class)
->middleware('auth');
Route::apiResource('issues', IssueController::class)
->middleware('auth');
Route::apiResource('support-articles', SupportArticleController::class)
->middleware('auth');
Route::apiResource('files', FileController::class)
->middleware('auth');
Route::apiResource('template-types', TemplateTypeController::class)
->middleware('auth');
Route::apiResource('templates', TemplateController::class)
->middleware('auth');
Route::apiResource('comments', CommentController::class)
->middleware('auth');
