<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\FullCalenderController;
  
// routes/web.php
Route::get('/fullcalender', [FullCalenderController::class, 'index']);
Route::post('/fullcalenderAjax', [FullCalenderController::class, 'ajax']);
Route::get('/agenda', [FullCalenderController::class, 'agenda']);
Route::get('/search-events', [FullCalenderController::class, 'searchEvents']);

