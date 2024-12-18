<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', [LandingPageController::class, 'showLandingPage'])->name('landing.page');

// Registration Routes
Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.process');

// Login and Logout Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth.check');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

// Protected Routes (requires authentication)
Route::middleware(['auth.check'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile/upload', [ProfileController::class, 'uploadProfilePicture'])->name('profile.upload');

    // Create community 
    Route::post('/communities', [CommunityController::class, 'store'])->name('community.store');
    Route::post('/community/{id}/join', [CommunityController::class, 'join'])->name('community.join');
    Route::post('/community/{id}/leave', [CommunityController::class, 'leave'])->name('community.leave');

    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');


    // Create post
    Route::post('/community/{community}/post', [PostController::class, 'store'])->name('post.store');
    Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/post/{post}/toggle-upvote', [PostController::class, 'toggleUpvote'])->name('post.toggleUpvote');
    Route::post('/post/{post}/toggle-downvote', [PostController::class, 'toggleDownvote'])->name('post.toggleDownvote');

    Route::get('/home', [CommunityController::class, 'index'])->name('home');
    Route::get('/community/{id}', [CommunityController::class, 'show'])->name('community.show');
    Route::get('/post/{post}', [PostController::class, 'show'])->name('post.show');
    Route::post('/posts/{post}/comment', [PostController::class, 'storeComment'])->name('post.comment');
});

Route::middleware(['auth.check', 'check.post.permissions'])->group(function () {
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.delete');
});

// Community management routes
Route::middleware(['auth.check', 'check.community.permissions'])->group(function () {
    Route::get('/community/{community}/settings', [CommunityController::class, 'settings'])->name('community.settings');
    Route::put('/community/{community}', [CommunityController::class, 'update'])->name('community.update');
    Route::post('/community/{community}/admin/{user}', [CommunityController::class, 'addAdmin'])->name('community.addAdmin');
    Route::delete('/community/{community}/admin/{user}', [CommunityController::class, 'removeAdmin'])->name('community.removeAdmin');
    Route::delete('/community/{community}', [CommunityController::class, 'destroy'])->name('community.delete');
});
