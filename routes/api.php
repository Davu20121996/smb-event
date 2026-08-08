<?php

use App\Http\Controllers\Api\V1\Admin\AmenitiesApiController;
use App\Http\Controllers\Api\V1\Admin\FaqsApiController;
use App\Http\Controllers\Api\V1\Admin\GalleriesApiController;
use App\Http\Controllers\Api\V1\Admin\HotelsApiController;
use App\Http\Controllers\Api\V1\Admin\PermissionsApiController;
use App\Http\Controllers\Api\V1\Admin\PricesApiController;
use App\Http\Controllers\Api\V1\Admin\RolesApiController;
use App\Http\Controllers\Api\V1\Admin\ScheduleApiController;
use App\Http\Controllers\Api\V1\Admin\SettingsApiController;
use App\Http\Controllers\Api\V1\Admin\SpeakersApiController;
use App\Http\Controllers\Api\V1\Admin\SponsorsApiController;
use App\Http\Controllers\Api\V1\Admin\UsersApiController;
use App\Http\Controllers\Api\V1\Admin\VenuesApiController;
use App\Http\Controllers\Api\V1\ZaloApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/zalo')->name('api.zalo.')->group(function () {
    Route::get('customer', [ZaloApiController::class, 'customer'])->name('customer');
    Route::get('qr-checkin', [ZaloApiController::class, 'qrCheckin'])->name('qr-checkin');
    Route::get('vouchers', [ZaloApiController::class, 'vouchers'])->name('vouchers');
    Route::get('events', [ZaloApiController::class, 'events'])->name('events');
    Route::get('event/{event}', [ZaloApiController::class, 'eventDetail'])->name('event');
    Route::post('register', [ZaloApiController::class, 'register'])->name('register');
});

Route::prefix('v1')->name('api.')->middleware('auth:api')->group(function () {
    // Permissions
    Route::apiResource('permissions', PermissionsApiController::class);

    // Roles
    Route::apiResource('roles', RolesApiController::class);

    // Users
    Route::apiResource('users', UsersApiController::class);

    // Settings
    Route::apiResource('settings', SettingsApiController::class);

    // Speakers
    Route::post('speakers/media', [SpeakersApiController::class, 'storeMedia'])->name('speakers.storeMedia');
    Route::apiResource('speakers', SpeakersApiController::class);

    // Schedules
    Route::apiResource('schedules', ScheduleApiController::class);

    // Venues
    Route::post('venues/media', [VenuesApiController::class, 'storeMedia'])->name('venues.storeMedia');
    Route::apiResource('venues', VenuesApiController::class);

    // Hotels
    Route::post('hotels/media', [HotelsApiController::class, 'storeMedia'])->name('hotels.storeMedia');
    Route::apiResource('hotels', HotelsApiController::class);

    // Galleries
    Route::post('galleries/media', [GalleriesApiController::class, 'storeMedia'])->name('galleries.storeMedia');
    Route::apiResource('galleries', GalleriesApiController::class);

    // Sponsors
    Route::post('sponsors/media', [SponsorsApiController::class, 'storeMedia'])->name('sponsors.storeMedia');
    Route::apiResource('sponsors', SponsorsApiController::class);

    // Faqs
    Route::apiResource('faqs', FaqsApiController::class);

    // Amenities
    Route::apiResource('amenities', AmenitiesApiController::class);

    // Prices
    Route::apiResource('prices', PricesApiController::class);
});
