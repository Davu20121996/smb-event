<?php

use App\Http\Controllers\Admin\AmenitiesController;
use App\Http\Controllers\Admin\AttendeesController;
use App\Http\Controllers\Admin\CheckinController;
use App\Http\Controllers\Admin\CompanyProfileController;
use App\Http\Controllers\Admin\CompanyProfileItemsController;
use App\Http\Controllers\Admin\ContactMessagesController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\GalleriesController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\HotelsController;
use App\Http\Controllers\Admin\KeyBenefitsController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\PricesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SpeakersController;
use App\Http\Controllers\Admin\SponsorsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\MenusController;
use App\Http\Controllers\Admin\LandingPagesController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\VenuesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('du-an', [HomeController::class, 'projects'])->name('projects');
Route::get('chia-se', [HomeController::class, 'shareIndex'])->name('share.index');
Route::get('chia-se/{landingPage:slug}', [HomeController::class, 'shareShow'])->name('share.show');
Route::post('chia-se/{landingPage:slug}/register', [HomeController::class, 'shareRegister'])->name('share.register')->middleware('throttle:10,1');
Route::get('chia-se/{landingPage:slug}/thank-you', [HomeController::class, 'shareThankYou'])->name('share.thank-you');
Route::get('posts', [HomeController::class, 'posts'])->name('posts.index');
Route::get('posts/tag/{tag}', [HomeController::class, 'postsByTag'])->name('posts.tag');
Route::get('posts/{post:slug}', [HomeController::class, 'post'])->name('posts.show');
Route::get('freshwork', [HomeController::class, 'freshwork'])->name('freshwork');
Route::get('event', [EventController::class, 'index'])->name('event');
Route::post('event/register', [EventController::class, 'register'])->name('event.register')->middleware('throttle:10,1');
Route::post('event/register-lead', [EventController::class, 'registerLead'])->name('event.register-lead')->middleware('throttle:10,1');
Route::get('event/thank-you', [EventController::class, 'thankYou'])->name('event.thank-you');
Route::get('event/confirm-attendance/{token}', [EventController::class, 'confirmAttendance'])->name('event.confirm');
Route::get('event/verify/{token}', [EventController::class, 'verifyAttendance'])->name('event.verify');
Route::get('event/ticket-qr/{attendee}', [EventController::class, 'ticketQr'])->name('event.ticket-qr');
Route::get('event/{event}', [EventController::class, 'show'])->name('event.show');
Route::post('contact', [HomeController::class, 'storeContact'])->name('contact.send')->middleware('throttle:10,1');
Route::get('speaker/{speaker}', [HomeController::class, 'view'])->name('speaker');
Route::redirect('/home', '/admin');

Route::post('locale', function () {
    $locale = request()->input('locale');
    if (in_array($locale, array_keys(config('panel.available_languages', [])), true)) {
        session()->put('language', $locale);
    }

    $previous = url()->previous();
    $target = (is_string($previous) && strlen($previous) > 0 && (parse_url($previous, PHP_URL_HOST) ?? '') === request()->getHost()) ? $previous : url('/');

    return redirect($target);
})->name('locale.switch');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('throttle:5,1');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');
    Route::get('huong-dan-su-dung', [HelpController::class, 'index'])->name('help');

    // Events
    Route::post('events/switch', [EventsController::class, 'switchEvent'])->name('events.switch');
    Route::delete('events/destroy', [EventsController::class, 'massDestroy'])->name('events.massDestroy');
    Route::post('events/{event}/media', [EventsController::class, 'storeMedia'])->name('events.storeMedia');
    Route::delete('events/{event}/remove-bg', [EventsController::class, 'removeBackgroundImage'])->name('events.remove-bg');
    Route::resource('events', EventsController::class);

    // Event inline child AJAX endpoints (used in events/{event}/edit tab panel)
    Route::prefix('events/{event}')->name('events.')->group(function () {
        // Speakers
        Route::post('speakers',          [SpeakersController::class, 'inlineStore'])->name('speakers.store');
        Route::put('speakers/{speaker}', [SpeakersController::class, 'inlineUpdate'])->name('speakers.update');
        Route::delete('speakers/{speaker}', [SpeakersController::class, 'inlineDestroy'])->name('speakers.destroy');

        // Schedules
        Route::post('schedules',            [ScheduleController::class, 'inlineStore'])->name('schedules.store');
        Route::put('schedules/{schedule}',  [ScheduleController::class, 'inlineUpdate'])->name('schedules.update');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'inlineDestroy'])->name('schedules.destroy');

        // Key Benefits
        Route::post('key-benefits',              [KeyBenefitsController::class, 'inlineStore'])->name('key-benefits.store');
        Route::put('key-benefits/{keyBenefit}',  [KeyBenefitsController::class, 'inlineUpdate'])->name('key-benefits.update');
        Route::delete('key-benefits/{keyBenefit}', [KeyBenefitsController::class, 'inlineDestroy'])->name('key-benefits.destroy');

        // Venues
        Route::post('venues',          [VenuesController::class, 'inlineStore'])->name('venues.store');
        Route::put('venues/{venue}',   [VenuesController::class, 'inlineUpdate'])->name('venues.update');
        Route::delete('venues/{venue}', [VenuesController::class, 'inlineDestroy'])->name('venues.destroy');

        // Hotels
        Route::post('hotels',          [HotelsController::class, 'inlineStore'])->name('hotels.store');
        Route::put('hotels/{hotel}',   [HotelsController::class, 'inlineUpdate'])->name('hotels.update');
        Route::delete('hotels/{hotel}', [HotelsController::class, 'inlineDestroy'])->name('hotels.destroy');

        // Galleries
        Route::post('galleries',            [GalleriesController::class, 'inlineStore'])->name('galleries.store');
        Route::put('galleries/{gallery}',   [GalleriesController::class, 'inlineUpdate'])->name('galleries.update');
        Route::delete('galleries/{gallery}', [GalleriesController::class, 'inlineDestroy'])->name('galleries.destroy');

        // Sponsors
        Route::post('sponsors',           [SponsorsController::class, 'inlineStore'])->name('sponsors.store');
        Route::put('sponsors/{sponsor}',  [SponsorsController::class, 'inlineUpdate'])->name('sponsors.update');
        Route::delete('sponsors/{sponsor}', [SponsorsController::class, 'inlineDestroy'])->name('sponsors.destroy');

        // FAQs
        Route::post('faqs',        [FaqsController::class, 'inlineStore'])->name('faqs.store');
        Route::put('faqs/{faq}',   [FaqsController::class, 'inlineUpdate'])->name('faqs.update');
        Route::delete('faqs/{faq}', [FaqsController::class, 'inlineDestroy'])->name('faqs.destroy');

        // Amenities
        Route::post('amenities',            [AmenitiesController::class, 'inlineStore'])->name('amenities.store');
        Route::put('amenities/{amenity}',   [AmenitiesController::class, 'inlineUpdate'])->name('amenities.update');
        Route::delete('amenities/{amenity}', [AmenitiesController::class, 'inlineDestroy'])->name('amenities.destroy');

        // Prices
        Route::post('prices',         [PricesController::class, 'inlineStore'])->name('prices.store');
        Route::put('prices/{price}',  [PricesController::class, 'inlineUpdate'])->name('prices.update');
        Route::delete('prices/{price}', [PricesController::class, 'inlineDestroy'])->name('prices.destroy');
    });

    // Posts
    Route::delete('posts/destroy', [PostsController::class, 'massDestroy'])->name('posts.massDestroy');
    Route::post('posts/media', [PostsController::class, 'storeMedia'])->name('posts.storeMedia');
    Route::resource('posts', PostsController::class);

    // Menus
    Route::delete('menus/destroy', [MenusController::class, 'massDestroy'])->name('menus.massDestroy');
    Route::resource('menus', MenusController::class);

    // Landing Pages
    Route::delete('landing-pages/destroy', [LandingPagesController::class, 'massDestroy'])->name('landing-pages.massDestroy');
    Route::post('landing-pages/media', [LandingPagesController::class, 'storeMedia'])->name('landing-pages.storeMedia');
    Route::resource('landing-pages', LandingPagesController::class);

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    // Settings
    Route::delete('settings/destroy', [SettingsController::class, 'massDestroy'])->name('settings.massDestroy');
    Route::resource('settings', SettingsController::class);

    // Speakers
    Route::delete('speakers/destroy', [SpeakersController::class, 'massDestroy'])->name('speakers.massDestroy');
    Route::post('speakers/media', [SpeakersController::class, 'storeMedia'])->name('speakers.storeMedia');
    Route::resource('speakers', SpeakersController::class);

    // Schedules
    Route::delete('schedules/destroy', [ScheduleController::class, 'massDestroy'])->name('schedules.massDestroy');
    Route::resource('schedules', ScheduleController::class);

    // Key Benefits
    Route::delete('key-benefits/destroy', [KeyBenefitsController::class, 'massDestroy'])->name('key-benefits.massDestroy');
    Route::post('key-benefits/media', [KeyBenefitsController::class, 'storeMedia'])->name('key-benefits.storeMedia');
    Route::resource('key-benefits', KeyBenefitsController::class);

    // Venues
    Route::delete('venues/destroy', [VenuesController::class, 'massDestroy'])->name('venues.massDestroy');
    Route::post('venues/media', [VenuesController::class, 'storeMedia'])->name('venues.storeMedia');
    Route::resource('venues', VenuesController::class);

    // Hotels
    Route::delete('hotels/destroy', [HotelsController::class, 'massDestroy'])->name('hotels.massDestroy');
    Route::post('hotels/media', [HotelsController::class, 'storeMedia'])->name('hotels.storeMedia');
    Route::resource('hotels', HotelsController::class);

    // Galleries
    Route::delete('galleries/destroy', [GalleriesController::class, 'massDestroy'])->name('galleries.massDestroy');
    Route::post('galleries/media', [GalleriesController::class, 'storeMedia'])->name('galleries.storeMedia');
    Route::resource('galleries', GalleriesController::class);

    // Sponsors
    Route::delete('sponsors/destroy', [SponsorsController::class, 'massDestroy'])->name('sponsors.massDestroy');
    Route::post('sponsors/media', [SponsorsController::class, 'storeMedia'])->name('sponsors.storeMedia');
    Route::resource('sponsors', SponsorsController::class);

    // Faqs
    Route::delete('faqs/destroy', [FaqsController::class, 'massDestroy'])->name('faqs.massDestroy');
    Route::resource('faqs', FaqsController::class);

    // Amenities
    Route::delete('amenities/destroy', [AmenitiesController::class, 'massDestroy'])->name('amenities.massDestroy');
    Route::resource('amenities', AmenitiesController::class);

    // Prices
    Route::delete('prices/destroy', [PricesController::class, 'massDestroy'])->name('prices.massDestroy');
    Route::resource('prices', PricesController::class);

    // Contact messages
    Route::delete('contacts/destroy', [ContactMessagesController::class, 'massDestroy'])->name('contacts.massDestroy');
    Route::resource('contacts', ContactMessagesController::class)->only(['index', 'show', 'destroy']);

    // Attendees
    Route::delete('attendees/destroy', [AttendeesController::class, 'massDestroy'])->name('attendees.massDestroy');
    Route::post('attendees/update-status', [AttendeesController::class, 'updateStatus'])->name('attendees.updateStatus');
    Route::post('attendees/export', [AttendeesController::class, 'export'])->name('attendees.export');
    Route::resource('attendees', AttendeesController::class)->only(['index', 'show', 'destroy']);
    Route::get('attendees/{attendee}/qr', [AttendeesController::class, 'showQr'])->name('attendees.qr');
    Route::post('attendees/{attendee}/generate-qr', [AttendeesController::class, 'generateQr'])->name('attendees.generateQr');
    Route::post('attendees/{attendee}/send-ticket', [AttendeesController::class, 'sendTicket'])->name('attendees.sendTicket');
    Route::post('attendees/{attendee}/send-verify', [AttendeesController::class, 'sendVerify'])->name('attendees.sendVerify');
    Route::post('attendees/{attendee}/send-voucher-email', [AttendeesController::class, 'sendVoucherEmail'])->name('attendees.sendVoucherEmail');
    Route::post('attendees/{attendee}/activate-voucher', [AttendeesController::class, 'activateVoucher'])->name('attendees.activateVoucher');
    Route::post('attendees/{attendee}/revoke-voucher', [AttendeesController::class, 'revokeVoucher'])->name('attendees.revokeVoucher');
    Route::get('attendees/{attendee}/voucher', [AttendeesController::class, 'voucherDetail'])->name('attendees.voucherDetail');

    // Vouchers
    Route::post('vouchers/destroy', [VoucherController::class, 'massDestroy'])->name('vouchers.massDestroy');
    Route::post('vouchers/generate-code', [VoucherController::class, 'generateCode'])->name('vouchers.generate-code');
    Route::post('vouchers/{voucher}/deactivate', [VoucherController::class, 'deactivate'])->name('vouchers.deactivate');
    Route::post('vouchers/{voucher}/assign', [VoucherController::class, 'assign'])->name('vouchers.assign');
    Route::get('vouchers/{voucher}/attendees', [VoucherController::class, 'attendees'])->name('vouchers.attendees');
    Route::resource('vouchers', VoucherController::class);

    // Check-in
    Route::get('checkin', [CheckinController::class, 'index'])->name('checkin.index');
    Route::post('checkin/scan', [CheckinController::class, 'scan'])->name('checkin.scan');

    // Company profile (home page content) - admin only
    Route::get('company-profile', [CompanyProfileController::class, 'index'])->name('company-profile.index');
    Route::put('company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    Route::post('company-profile/items/media', [CompanyProfileItemsController::class, 'storeMedia'])->name('company-profile.items.storeMedia');
    Route::get('company-profile/items/{section?}', [CompanyProfileItemsController::class, 'index'])->name('company-profile.items');
    Route::post('company-profile/items', [CompanyProfileItemsController::class, 'store'])->name('company-profile.items.store');
    Route::get('company-profile/items/{item}/edit', [CompanyProfileItemsController::class, 'edit'])->name('company-profile.items.edit');
    Route::put('company-profile/items/{item}', [CompanyProfileItemsController::class, 'update'])->name('company-profile.items.update');
    Route::delete('company-profile/items/{item}', [CompanyProfileItemsController::class, 'destroy'])->name('company-profile.items.destroy');
    Route::get('company-profile/items/{item}/up', [CompanyProfileItemsController::class, 'up'])->name('company-profile.items.up');
    Route::get('company-profile/items/{item}/down', [CompanyProfileItemsController::class, 'down'])->name('company-profile.items.down');
});
