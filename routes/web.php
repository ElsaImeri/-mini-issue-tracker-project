<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('issues', IssueController::class);
    Route::resource('tags', TagController::class);

    Route::post('/tags/api', [TagController::class, 'storeApi'])->name('tags.store.api');
    
    Route::post('/issues/{issue}/update-tags', [IssueController::class, 'updateTags'])->name('issues.update-tags');
    Route::post('/issues/{issue}/update-assigned-users', [IssueController::class, 'updateAssignedUsers'])->name('issues.update-assigned-users');
    Route::get('/issues/{issue}/comments', [IssueController::class, 'getComments'])->name('issues.comments.get');
    Route::post('/issues/{issue}/comments', [IssueController::class, 'storeComment'])->name('issues.comments.store');

    Route::post('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
    Route::post('/issues/search', [IssueController::class, 'search'])->name('issues.search');
});

require __DIR__.'/auth.php';