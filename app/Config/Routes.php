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
$routes->get('public-events', 'PublicSite::events');
$routes->get('api/events', 'PublicSite::apiEvents');
$routes->get('events', 'PublicSite::events');

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
    $routes->get('results/delete/(:any)', 'Admin\Results::delete/$1');

    // Fixtures
    $routes->get('fixtures', 'Admin\Fixtures::index');
    $routes->get('fixtures/create', 'Admin\Fixtures::create');
    $routes->post('fixtures/store', 'Admin\Fixtures::store');
    $routes->post('fixtures/update', 'Admin\Fixtures::update');
    $routes->get('fixtures/delete/(:any)', 'Admin\Fixtures::delete/$1');

    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/test', 'Admin\Users::test');
    $routes->get('users/approve/(:any)', 'Admin\Users::approve/$1');
    $routes->get('users/get/(:any)', 'Admin\Users::getUser/$1');
    $routes->post('users/update/(:any)', 'Admin\Users::update/$1');
    $routes->post('users/unlock/(:any)', 'Admin\Users::unlock/$1');
    $routes->get('users/print/(:any)', 'Admin\Users::printProfile/$1');
    $routes->get('users/pdf/(:any)', 'Admin\Users::pdf/$1');
    $routes->get('verify/(:any)', 'Verify::index/$1');
    $routes->get('users/card/(:any)', 'Admin\Users::generateCard/$1');
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
    $routes->get('products/edit/(:any)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:any)', 'Admin\Products::update/$1');

    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');

    $routes->get('purchaseorders', 'Admin\PurchaseOrders::index');
    $routes->get('purchaseorders/create', 'Admin\PurchaseOrders::create');
    $routes->post('purchaseorders/store', 'Admin\PurchaseOrders::store');

    $routes->get('purchaseorders/view/(:any)', 'Admin\PurchaseOrders::view/$1');
    $routes->get('purchaseorders/edit/(:any)', 'Admin\PurchaseOrders::edit/$1');
    $routes->get('purchaseorders/pdf/(:any)', 'Admin\PurchaseOrders::pdf/$1');
    $routes->post('purchaseorders/update/(:any)', 'Admin\PurchaseOrders::update/$1');

    $routes->get('purchaseorders/approve/(:any)', 'Admin\PurchaseOrders::approve/$1');
    $routes->post('purchaseorders/saveApproval', 'Admin\PurchaseOrders::saveApproval');

    $routes->get('purchaseorders/receive/(:any)', 'Admin\PurchaseOrders::receive/$1');
    $routes->post('purchaseorders/processReceive', 'Admin\PurchaseOrders::processReceive');

    $routes->get('stock/transfer', 'StockController::transfer', ['filter' => 'auth']);
    $routes->post('stock/transfer', 'StockController::doTransfer', ['filter' => 'auth']);

    $routes->get('reports/sales', 'ReportsController::salesReport', ['filter' => 'auth']);
    $routes->get('reports/sales-grouped', 'ReportsController::salesGrouped', ['filter' => 'auth']);
    $routes->get('reports', 'ReportsController::dashboard', ['filter' => 'auth']);
    $routes->get('reports/forecast', 'ReportsController::forecast');
    $routes->get('reports/sales-graph', 'ReportsController::salesGraph');
    $routes->get('reports/sales-by-product-graph', 'ReportsController::salesByProductGraph');

    // Specials (under Stock)
    $routes->get('stock/specials', 'Admin\Specials::index');
    $routes->get('stock/specials/create', 'Admin\Specials::create');
    $routes->post('stock/specials/create', 'Admin\Specials::store');
    $routes->get('stock/specials/edit/(:any)', 'Admin\Specials::edit/$1');
    $routes->post('stock/specials/edit/(:any)', 'Admin\Specials::update/$1');
    $routes->post('stock/specials/delete/(:any)', 'Admin\Specials::delete/$1');

    // Suppliers
    $routes->group('suppliers', function($routes) {
        $routes->get('/', 'Admin\Suppliers::index');
        $routes->match(['GET','POST'], 'create', 'Admin\Suppliers::create');
        $routes->match(['GET','POST'], 'edit/(:any)', 'Admin\Suppliers::edit/$1');
        $routes->get('view/(:any)', 'Admin\Suppliers::view/$1');
    });

    // Events (admin)
    $routes->get('events', 'Admin\Events::index');
    $routes->get('events/calendar', 'Admin\Events::calendar');
    $routes->get('events/list', 'Admin\Events::list');
    $routes->post('events/create', 'Admin\Events::create');
    $routes->post('events/update/(:any)', 'Admin\Events::update/$1');
    $routes->post('events/delete/(:any)', 'Admin\Events::delete/$1');

    // Events (Admin AJAX CRUD)
    $routes->group('events', function($routes) {
        $routes->get('/', 'Admin\Events::index'); // Admin calendar view
        $routes->get('list', 'Admin\Events::list'); // AJAX: fetch events
        $routes->post('create', 'Admin\Events::create'); // AJAX: create event
        $routes->post('update/(:any)', 'Admin\Events::update/$1'); // AJAX: update event
        $routes->post('delete/(:any)', 'Admin\Events::delete/$1'); // AJAX: delete event
        // Optionally: categories, images, recurrence endpoints here
    });

    // Team Selection (Admin)
    $routes->get('teams', 'Admin\TeamSelection::index'); // List and view teams
    $routes->get('teams/view/(:any)', 'Admin\TeamSelection::view/$1'); // View single team
    $routes->get('teams/print/(:any)', 'Admin\TeamSelection::printPdf/$1'); // Print team as PDF
    $routes->match(['GET','POST'], 'teams/create/(:any)', 'Admin\TeamSelection::create/$1'); // Create for fixture (filter removed for debug)
    $routes->match(['GET','POST'], 'teams/edit/(:any)', 'Admin\TeamSelection::edit/$1', ['filter' => 'role:coach']); // Edit team
    $routes->post('teams/delete/(:any)', 'Admin\TeamSelection::delete/$1', ['filter' => 'role:coach']); // Delete team
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
    $routes->get('tab-totals', 'POSController::tabTotals');
    $routes->post('pay-tab', 'POSController::payTab');
    // Add location endpoints
    $routes->get('locations', 'POSController::locations');
    $routes->post('add-location', 'POSController::addLocation');
    // Specials POS API
    $routes->post('sell-special', 'POSController::sellSpecial');
});

// Specials API for POS (fetching specials and items)
$routes->group('api/specials', function($routes) {
    $routes->get('/', 'POSController::apiSpecials');
    $routes->get('(:num)/items', 'POSController::apiSpecialItems/$1');
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

$routes->post('credits/add', 'POSController::addCredit');
    $routes->get('credits/list', 'POSController::listCredits');
    $routes->post('credits/redeem', 'POSController::redeemCreditDrinks');