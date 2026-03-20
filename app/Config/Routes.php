<?php

use CodeIgniter\Router\RouteCollection;
use Config\Services;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultMethod('index');
$routes->setAutoRoute(false);

/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE (NO AUTH)
|--------------------------------------------------------------------------
*/
$routes->get('/', 'PublicSite::home');
$routes->get('about', 'PublicSite::about');
$routes->get('teams', 'PublicSite::teams');
$routes->get('fixtures', 'PublicSite::fixtures');
$routes->get('results', 'PublicSite::results');
$routes->get('contact', 'PublicSite::contact');

/*
|--------------------------------------------------------------------------
| PLAYER SETUP (LOGGED IN ONLY)
|--------------------------------------------------------------------------
*/
$routes->get('player/profile/setup', 'Player\Profile::setup', ['filter'=>'auth']);

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::authenticate');
$routes->get('logout', 'Auth\Login::logout'); 

$routes->get('register', 'Front\Register::index');
$routes->post('register/store', 'Front\Register::store');
$routes->post('register/check-email', 'Front\Register::checkEmail');
$routes->get('activate/(:any)', 'Front\Activate::index/$1');

/*
|--------------------------------------------------------------------------
| ADMIN AREA (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/
$routes->group('admin', ['filter' => ['auth','adminViewData'] ], function ($routes) {


    $routes->get('dashboard', 'Admin\Dashboard::index');

    $routes->get('dashboard-default', 'Dashboard::default');

    // Settings
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');

    // Results
    $routes->get('results', 'Admin\Results::index');
    $routes->get('results/create', 'Admin\Results::create');
    $routes->post('results/store', 'Admin\Results::store');
    $routes->get('results/delete/(:num)', 'Admin\Results::delete/$1');

    // Fixtures
    $routes->get('fixtures', 'Admin\Fixtures::index');
    $routes->get('fixtures/create', 'Admin\Fixtures::create');
    $routes->post('fixtures/store', 'Admin\Fixtures::store');
    $routes->post('fixtures/update', 'Admin\Fixtures::update');
    $routes->get('fixtures/delete/(:num)', 'Admin\Fixtures::delete/$1');

    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/test', 'Admin\Users::test');
    $routes->get('users/approve/(:num)', 'Admin\Users::approve/$1');
    $routes->get('users/get/(:num)', 'Admin\Users::getUser/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->post('users/unlock/(:num)', 'Admin\Users::unlock/$1');
    $routes->get('users/print/(:num)', 'Admin\Users::printProfile/$1');
    $routes->get('users/pdf/(:num)', 'Admin\Users::pdf/$1');
    $routes->get('verify/(:any)', 'Verify::index/$1');
    $routes->get('users/card/(:num)', 'Admin\Users::generateCard/$1');
    $routes->get('users/print-cards', 'Admin\Users::printCards');

    //POS
    $routes->get('pos', 'Admin\POSController::index');
    $routes->get('stock', 'Admin\Stock::index');

    $routes->get('stock/po/create', 'Admin\PurchaseOrders::create');
    $routes->post('stock/po/store', 'Admin\PurchaseOrders::store');
    $routes->get('stock/po', 'Admin\PurchaseOrders::index');

    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/store', 'Admin\Products::store');

    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');

    $routes->get('purchaseorders', 'Admin\PurchaseOrders::index');
    $routes->get('purchaseorders/create', 'Admin\PurchaseOrders::create');
    $routes->post('purchaseorders/store', 'Admin\PurchaseOrders::store');

    $routes->get('purchaseorders/view/(:num)', 'Admin\PurchaseOrders::view/$1');
    $routes->get('purchaseorders/edit/(:num)', 'Admin\PurchaseOrders::edit/$1');
    $routes->get('purchaseorders/pdf/(:num)', 'Admin\PurchaseOrders::pdf/$1');
    $routes->post('purchaseorders/update/(:num)', 'Admin\PurchaseOrders::update/$1');

    $routes->get('purchaseorders/approve/(:num)', 'Admin\PurchaseOrders::approve/$1');
    $routes->post('purchaseorders/saveApproval', 'Admin\PurchaseOrders::saveApproval');

    $routes->get('purchaseorders/receive/(:num)', 'Admin\PurchaseOrders::receive/$1');
    $routes->post('purchaseorders/processReceive', 'Admin\PurchaseOrders::processReceive');

});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

$routes->group('pos', function($routes){

    $routes->get('init', 'POSController::init');
    $routes->post('sale', 'POSController::createSale');
    $routes->post('redeem-credit', 'POSController::redeemCredit');
    $routes->post('open-tab', 'POSController::openTab');
    $routes->post('close-tab', 'POSController::closeTab');
    $routes->get('credits','POSController::credits');

    // ✅ FIXED
    $routes->get('tab-totals', 'POSController::tabTotals');
    $routes->post('pay-tab', 'POSController::payTab');

});

$routes->group('stock', function($routes){

    $routes->get('/', 'StockController::index');

    $routes->post('receive','StockController::receiveStock');

    $routes->post('transfer','StockController::transferStock');

});

/*
|--------------------------------------------------------------------------
| PLAYER AREA (ROLE FILTER)
|--------------------------------------------------------------------------
*/

$routes->group('player', ['filter' => 'role:player'], function ($routes) {

    $routes->get('dashboard', 'Player\Dashboard::index');

    // Profile wizard
    $routes->post('profile/save', 'Player\Profile::save');
    $routes->post('profile/autosave','Player\Profile::autosave');

});

/*
|--------------------------------------------------------------------------
| COACH AREA (ROLE FILTER)
|--------------------------------------------------------------------------
*/
$routes->group('coach', ['filter' => 'role:coach'], function ($routes) {
    $routes->get('dashboard', 'Coach\Dashboard::index');
});
