<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VerifyEmailController;
use App\Http\Controllers\ResendVerifyEmailController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrgnizeController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\SpeakingController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\SponsoringController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamEventController;
use App\Http\Controllers\TeamEventTicketController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;

use App\Http\Controllers\ImagesController;

use App\Http\Controllers\SendEmailController;
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

// User Authentication route group
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // Reset password
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class);

    // Social login routes
    Route::get('/login/{service}', [SocialAuthController::class, 'redirect']);
    Route::get('/login/{service}/callback', [SocialAuthController::class, 'callback']);
});

// Verify email
Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');

// Resend link to verify email
Route::post('/email/verify/resend', ResendVerifyEmailController::class)
    ->middleware(['auth:api', 'throttle:6,1'])
    ->name('verification.send');


Route::apiResource('events', EventController::class);




Route::apiResource('categories', CategoryController::class);
Route::apiResource('followings', FollowingController::class);
Route::apiResource('locations', LocationController::class);
Route::apiResource('members', MemberController::class);
Route::apiResource('orgnizes', OrgnizeController::class);
Route::apiResource('speakers', SpeakerController::class);
Route::apiResource('speakings', SpeakingController::class);
Route::apiResource('sponsors', SponsorController::class);
Route::apiResource('sponsorings', SponsoringController::class);
Route::apiResource('permissions', PermissionController::class);
Route::apiResource('roles', RoleController::class);

// Route::apiResource('events.tickets', EventTicketController::class);


/*
// Manage Teams
*/
Route::apiResource('teams', TeamController::class);

Route::put(
    '/teams/{team}/events/{event}/checkin',
    [TeamEventController::class, 'checkin']
);
Route::put(
    '/teams/{team}/events/{event}/checkout',
    [TeamEventController::class, 'checkout']
);
Route::apiResource('teams.events', TeamEventController::class);

Route::put(
    '/teams/{team}/events/{event}/tickets/{ticket}/checkin',
    [TeamEventTicketController::class, 'checkin']
);
Route::put(
    '/teams/{team}/events/{event}/tickets/{ticket}/checkout',
    [TeamEventTicketController::class, 'checkout']
);
Route::apiResource('teams.events.tickets', TeamEventTicketController::class);


/*
// Manage Roles Permissions
*/
Route::apiResource('roles.permissions', RolePermissionController::class);


/*
// Manage Users Roles
*/
Route::apiResource('users.roles', UserRoleController::class);


Route::apiResource('tickets', TicketController::class);
Route::apiResource('users', UserController::class);


// Protected routes group
Route::group([], function () {
    Route::apiResource('images', ImagesController::class);
});



// tests 
Route::get('/send-email', [SendEmailController::class, 'sendEmail']);
