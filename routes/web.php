<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'cronjob', 'namespace' => 'Cronjob'], function() {
    Route::prefix('order')->group(function () {
        Route::get('/refund-order', [\App\Http\Controllers\Cronjob\OrderController::class, 'refund']);
        Route::get('/cancel-order', [\App\Http\Controllers\Cronjob\OrderController::class, 'cancel']);
        Route::get('/send-order', [\App\Http\Controllers\Cronjob\OrderController::class, 'send']);
        Route::get('/status-order', [\App\Http\Controllers\Cronjob\OrderController::class, 'status']);
    });
    Route::get('/check-status-order', function () {
        \Artisan::call('check_status_orders:cron');
        exit('done');
    });
    Route::get('/runQueueDolananKode', function () {
        \Artisan::call('schedule:run');
        exit('done');
    });
    Route::prefix('service')->group(function () {
        Route::get('/get-game/{provider:id}', [\App\Http\Controllers\Cronjob\ServiceController::class, 'getGame']);
        Route::get('/sync/{provider:id}', [\App\Http\Controllers\Cronjob\ServiceController::class, 'sync']);
        Route::get('/get-ppob/{provider:id}', [\App\Http\Controllers\Cronjob\ServiceController::class, 'getPPOB']);
    });

});

Route::get('/', [\App\Http\Controllers\Primary\HomeController::class, 'index'])->withoutMiddleware(['auth']);
Route::prefix('callback')->group(function () {
    Route::post('/tripay', [\App\Http\Controllers\CallbackController::class, 'tripay']);
    Route::post('/xendit', [\App\Http\Controllers\CallbackController::class, 'xendit']);
    Route::post('/paydisini', [\App\Http\Controllers\CallbackController::class, 'paydisini']);
});

Route::prefix('ajax')->group(function () {
    Route::group(['prefix' => 'product'], function() {
        Route::post('/get-detail/{serviceCategory:id}', [\App\Http\Controllers\Primary\ProductController::class, 'getDetail'])->name('product.get-detail');
        Route::post('/get-price', [\App\Http\Controllers\Primary\ProductController::class, 'getPrice'])->name('product.get-price');
    });
    Route::group(['prefix' => 'deposit'], function() {
        Route::post('/get-price', [\App\Http\Controllers\Primary\DepositController::class, 'getPrice'])->name('deposit.get-price');
    });
    Route::group(['prefix' => 'account/upgrade'], function() {
        Route::post('/get-price', [\App\Http\Controllers\Primary\AccountController::class, 'getPriceUpgrade'])->name('account.upgrade.get-price');
    });
    Route::get('/product/search', [\App\Http\Controllers\Primary\ProductController::class, 'search'])->name('product.search');
    Route::post('/product/get-detail/{serviceCategory:id}', [\App\Http\Controllers\Primary\ProductController::class, 'getDetail'])->name('product.get-detail');
    Route::post('/product/get-price', [\App\Http\Controllers\Primary\ProductController::class, 'getPrice'])->name('product.get-price');
});
Route::group(['namespace' => 'Primary', 'middleware' => ['auth']], function() {
    Route::get('/', [\App\Http\Controllers\Primary\HomeController::class, 'index'])->withoutMiddleware(['auth']);
    Route::get('/home', [\App\Http\Controllers\Primary\HomeController::class, 'index'])->withoutMiddleware(['auth'])->name('home');
    Route::group(['prefix' => 'product'], function() {
        Route::get('/{target:slug}', [\App\Http\Controllers\Primary\ProductController::class, 'category'])->withoutMiddleware(['auth'])->name('product.category');
        Route::get('/{type}/category/{category}', [\App\Http\Controllers\Primary\ProductController::class, 'order'])->withoutMiddleware(['auth'])->name('product.order');
    });
    Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function() {
        Route::get('/login', [\App\Http\Controllers\Primary\Auth\LoginController::class, 'view'])->withoutMiddleware(['auth'])->name('auth.login.get');
        Route::post('/login', [\App\Http\Controllers\Primary\Auth\LoginController::class, 'login'])->withoutMiddleware(['auth'])->name('auth.login.post');
        Route::get('/register', [\App\Http\Controllers\Primary\Auth\RegisterController::class, 'view'])->withoutMiddleware(['auth'])->name('auth.register.get');
        Route::post('/register', [\App\Http\Controllers\Primary\Auth\RegisterController::class, 'register'])->withoutMiddleware(['auth'])->name('auth.register.post');
        Route::get('/forgot-password', [\App\Http\Controllers\Primary\Auth\ForgotPasswordController::class, 'view'])->withoutMiddleware(['auth'])->name('auth.forgot-password.get');
        Route::post('/forgot-password', [\App\Http\Controllers\Primary\Auth\ForgotPasswordController::class, 'forgotPassword'])->withoutMiddleware(['auth'])->name('auth.forgot-password.post');
        Route::get('/reset-password/{user_token:token}', [\App\Http\Controllers\Primary\Auth\ResetPasswordController::class, 'view'])->withoutMiddleware(['auth'])->name('auth.reset-password.get');
        Route::post('/reset-password/{user_token:token}', [\App\Http\Controllers\Primary\Auth\ResetPasswordController::class, 'resetPassword'])->withoutMiddleware(['auth'])->name('auth.reset-password.post');
        Route::get('/logout', [\App\Http\Controllers\Primary\Auth\LoginController::class, 'logout'])->withoutMiddleware(['guest'])->name('logout');
    });
    Route::group(['prefix' => 'reviews'], function() {
        Route::get('/', [\App\Http\Controllers\Primary\ReviewController::class, 'index'])->withoutMiddleware(['auth'])->name('review.index');
    });
    Route::group(['prefix' => 'tools'], function() {
        Route::get('/{type}', [\App\Http\Controllers\Primary\ToolController::class, 'index'])->withoutMiddleware(['auth'])->name('tool.index');
    });
    Route::get('/service', [\App\Http\Controllers\Primary\ServiceController::class, 'index'])->withoutMiddleware(['auth'])->name('service.index');
    Route::group(['prefix' => 'page'], function() {
        Route::get('/sitemap/{slug}', [\App\Http\Controllers\Primary\PageController::class, 'sitemap'])->withoutMiddleware(['auth'])->name('page.sitemap');
    });
    Route::group(['prefix' => 'order'], function() {
        Route::post('/checkout', [\App\Http\Controllers\Primary\OrderController::class, 'checkout'])->withoutMiddleware(['auth'])->name('order.checkout');
        Route::get('/search', [\App\Http\Controllers\Primary\OrderController::class, 'search'])->withoutMiddleware(['auth'])->name('order.search.get');
        Route::post('/search', [\App\Http\Controllers\Primary\OrderController::class, 'postSearch'])->withoutMiddleware(['auth'])->name('order.search.post');
        Route::get('/invoice/{invoice}', [\App\Http\Controllers\Primary\OrderController::class, 'invoice'])->withoutMiddleware(['auth'])->name('order.invoice');
        Route::get('/history', [\App\Http\Controllers\Primary\OrderController::class, 'history'])->name('order.history');
        Route::get('/notif', [\App\Http\Controllers\Primary\OrderController::class, 'popUpNotification'])->withoutMiddleware(['auth'])->name('order.pop-up-notification');
        Route::post('/review/{invoice}', [\App\Http\Controllers\Primary\OrderController::class, 'postReview'])->name('order.review.post');
    });
    Route::group(['prefix' => 'deposit'], function() {
        Route::get('/', [\App\Http\Controllers\Primary\DepositController::class, 'index'])->name('deposit.index');
        Route::post('/request', [\App\Http\Controllers\Primary\DepositController::class, 'request'])->name('deposit.request');
        Route::get('/history', [\App\Http\Controllers\Primary\DepositController::class, 'history'])->name('deposit.history');
        Route::get('/invoice/{invoice}', [\App\Http\Controllers\Primary\DepositController::class, 'invoice'])->name('deposit.invoice');
    });
    Route::group(['prefix' => 'account'], function() {
        Route::get('/', [\App\Http\Controllers\Primary\AccountController::class, 'index'])->name('account.index');
        Route::get('/upgrade', [\App\Http\Controllers\Primary\AccountController::class, 'upgrade'])->name('account.upgrade.get');
        Route::post('/upgrade', [\App\Http\Controllers\Primary\AccountController::class, 'postUpgrade'])->name('account.upgrade.post');
        Route::get('/upgrade/invoice/{invoice}', [\App\Http\Controllers\Primary\AccountController::class, 'upgradeInvoice'])->name('account.upgrade.invoice');
        Route::get('/upgrade/history', [\App\Http\Controllers\Primary\AccountController::class, 'upgradeHistory'])->name('account.upgrade.history');
        Route::get('/mutation', [\App\Http\Controllers\Primary\AccountController::class, 'mutation'])->name('account.mutation');
        Route::post('/update', [\App\Http\Controllers\Primary\AccountController::class, 'update'])->name('account.update');
        Route::post('/update/api', [\App\Http\Controllers\Primary\AccountController::class, 'updateAPI'])->name('account.updateAPI');
        Route::post('/update/generate-api-key', [\App\Http\Controllers\Primary\AccountController::class, 'generateAPIKey'])->name('account.generateAPIKey');
    });
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:admin']], function() {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    Route::get('/login', [\App\Http\Controllers\Admin\LoginController::class, 'index'])->name('admin.login.get')->withoutMiddleware(['auth:admin']);
    Route::post('/login', [\App\Http\Controllers\Admin\LoginController::class, 'action'])->name('admin.login.post')->withoutMiddleware(['auth:admin']);
    Route::get('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // ADMIN //
    Route::resource('admin', '\App\Http\Controllers\Admin\AdminController', ['as' => 'admin']);
    Route::get('/admin/switch/{admin:id}', [\App\Http\Controllers\Admin\AdminController::class, 'switchStatus']);

    // ADMIN LOG //
    Route::resource('admin-log', '\App\Http\Controllers\Admin\AdminLogController', ['as' => 'admin']);

    // USER //
    Route::resource('user', '\App\Http\Controllers\Admin\UserController', ['as' => 'admin']);
    Route::get('/user/switch/{user:id}', [\App\Http\Controllers\Admin\UserController::class, 'switchStatus']);

    // PROVIDER //
    Route::resource('provider', '\App\Http\Controllers\Admin\ProviderController', ['as' => 'admin']);
    Route::get('/provider/switch/{provider:id}', [\App\Http\Controllers\Admin\ProviderController::class, 'switchStatus']);
    Route::group(['prefix' => 'provider/service'], function(){
        Route::get('/{provider:id}', [\App\Http\Controllers\Admin\ProviderController::class, 'service'])->name('admin.provider.service');
        Route::post('/{provider:id}/category', [\App\Http\Controllers\Admin\ProviderController::class, 'serviceCategory'])->name('admin.provider.service.category');
        Route::post('/{provider:id}/service/list', [\App\Http\Controllers\Admin\ProviderController::class, 'serviceGet'])->name('admin.provider.service.get');
        Route::get('/{provider:id}/service/{provider_service_code}', [\App\Http\Controllers\Admin\ProviderController::class, 'serviceCreate'])->name('admin.provider.service.service_create');
        Route::get('/{provider:id}/sync', [\App\Http\Controllers\Admin\ProviderController::class, 'serviceSyncProvider'])->name('admin.provider.service_sync');
    });
    Route::get('/provider/balance/{provider:id}', [\App\Http\Controllers\Admin\ProviderController::class, 'getBalance'])->name('admin.provider.balance');

    // SERVICE CATEGORY TYPE //
    Route::resource('service-category-type', '\App\Http\Controllers\Admin\ServiceCategoryTypeController', ['as' => 'admin']);
    Route::get('/service-category-type/switch/{serviceCategoryType:id}', [\App\Http\Controllers\Admin\ServiceCategoryTypeController::class, 'switchStatus']);

    // SERVICE CATEGORY //
    Route::resource('service-category', '\App\Http\Controllers\Admin\ServiceCategoryController', ['as' => 'admin']);
    Route::get('/service-category/switch/{serviceCategory:id}', [\App\Http\Controllers\Admin\ServiceCategoryController::class, 'switchStatus']);

    // SERVICE SUB CATEGORY
    Route::group(['prefix' => 'service-sub-category'], function(){
        Route::get('/sort', [\App\Http\Controllers\Admin\ServiceSubCategoryController::class, 'sortGET']);
        Route::post('/sort', [\App\Http\Controllers\Admin\ServiceSubCategoryController::class, 'sortPOST']);
    });
    Route::resource('service-sub-category', '\App\Http\Controllers\Admin\ServiceSubCategoryController', ['as' => 'admin']);

    // SERVICE //
    Route::resource('service', '\App\Http\Controllers\Admin\ServiceController', ['as' => 'admin']);
    Route::get('/service/switch/{service:id}', [\App\Http\Controllers\Admin\ServiceController::class, 'switchStatus']);
    Route::post('/service/{provider:id}/store-mass', [\App\Http\Controllers\Admin\ServiceController::class, 'storeMass'])->name('admin.service.storeMass');

    // PAYMENT METHOD //
    Route::resource('payment-method', '\App\Http\Controllers\Admin\PaymentMethodController', ['as' => 'admin']);
    Route::get('/payment-method/switch/{paymentMethod:id}', [\App\Http\Controllers\Admin\PaymentMethodController::class, 'switchStatus']);

    // DEPOSIT //
    Route::resource('deposit', '\App\Http\Controllers\Admin\DepositController', ['as' => 'admin']);
    Route::group(['prefix' => 'deposit'], function() {
        Route::post('/{deposit:id}/status/{status}', [\App\Http\Controllers\Admin\DepositController::class, 'changeStatus'])->name('admin.deposit.status');
        Route::post('/{deposit:id}/paid/{paid}', [\App\Http\Controllers\Admin\DepositController::class, 'confirmPaid'])->name('admin.deposit.paid');
    });

    // ORDER //
    Route::resource('order', '\App\Http\Controllers\Admin\OrderController', ['as' => 'admin']);
    Route::group(['prefix' => 'order'], function() {
        Route::post('/{order:id}/status/{status}', [\App\Http\Controllers\Admin\OrderController::class, 'changeStatus'])->name('admin.order.status');
        Route::post('/{order:id}/paid/{paid}', [\App\Http\Controllers\Admin\OrderController::class, 'confirmPaid'])->name('admin.order.paid');
    });

    // USER UPGRADE //
    Route::resource('user-upgrade', '\App\Http\Controllers\Admin\UserUpgradeController', ['as' => 'admin']);
    Route::group(['prefix' => 'user-upgrade'], function() {
        Route::post('/{userUpgrade:id}/status/{status}', [\App\Http\Controllers\Admin\UserUpgradeController::class, 'changeStatus'])->name('admin.user-upgrade.status');
        Route::post('/{userUpgrade:id}/paid/{paid}', [\App\Http\Controllers\Admin\UserUpgradeController::class, 'confirmPaid'])->name('admin.user-upgrade.paid');
    });

    // BANNER //
    Route::resource('banner', '\App\Http\Controllers\Admin\BannerController', ['as' => 'admin']);

    // PROVIDER API LOG //
    Route::resource('provider-api-log', '\App\Http\Controllers\Admin\ProviderApiLogController', ['as' => 'admin']);

    // LAPORAN //
    Route::group(['prefix' => 'report'], function() {
        Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.report.index');
        Route::get('/order', [\App\Http\Controllers\Admin\ReportController::class, 'order'])->name('admin.report.order');
        Route::get('/deposit', [\App\Http\Controllers\Admin\ReportController::class, 'deposit'])->name('admin.report.deposit');
        Route::get('/upgrade-level', [\App\Http\Controllers\Admin\ReportController::class, 'upgradeLevel'])->name('admin.report.upgrade-level');
    });

    // LAPORAN CHART//
    Route::group(['prefix' => 'report-chart'], function() {
        Route::get('/', [\App\Http\Controllers\Admin\ReportChartController::class, 'index'])->name('admin.report-chart.index');
        Route::get('/order', [\App\Http\Controllers\Admin\ReportChartController::class, 'order'])->name('admin.report-chart.order');
        Route::get('/deposit', [\App\Http\Controllers\Admin\ReportChartController::class, 'deposit'])->name('admin.report-chart.deposit');
        Route::get('/upgrade-level', [\App\Http\Controllers\Admin\ReportChartController::class, 'upgradeLevel'])->name('admin.report-chart.upgrade-level');
    });

    // WEBSITE CONFIG //
    Route::group(['prefix' => 'website-config'], function() {
        Route::get('/{type?}', [\App\Http\Controllers\Admin\WebsiteConfigController::class, 'index'])->name('admin.website-config.get');
        Route::post('/{type?}', [\App\Http\Controllers\Admin\WebsiteConfigController::class, 'update'])->name('admin.website-config.type');
        Route::post('/mail/test', [\App\Http\Controllers\Admin\WebsiteConfigController::class, 'mailTest'])->name('admin.website-config.mail.test');
    });

    // BALANCE MUTATION //
    Route::resource('balance-mutation', '\App\Http\Controllers\Admin\BalanceMutationController', ['as' => 'admin']);

    // USER LEVEL //
    Route::resource('user-level', '\App\Http\Controllers\Admin\UserLevelController', ['as' => 'admin']);

    // PAGE //
    Route::resource('page', '\App\Http\Controllers\Admin\PageController', ['as' => 'admin']);
    Route::get('/page/switch/{page:id}', [\App\Http\Controllers\Admin\PageController::class, 'switchStatus']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
