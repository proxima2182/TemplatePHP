<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
 $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/get-session', 'Home::getSession');
$routes->get('/set-session', 'Home::setSession');
$routes->get('/profile', 'ProfileController::index');
$routes->get('/board/grid(/([0-9]*))*', 'BoardController::getGridBoard/$1');
$routes->get('/board/table(/([0-9]*))*', 'BoardController::getTableBoard/$1');
$routes->get('/board/detail/([0-9]+)', 'BoardController::getBoardDetail/$1');
//admin pages
$routes->get('/admin/board(/([0-9]*))*', 'AdminController::getBoard/$1');


//api
$routes->get('/api/board/get/([0-9]+)', '\App\Controllers\API\Board::getBoard/$1');
$routes->get('/api/board/get/([0-9]+)/reply', '\App\Controllers\API\Board::getReply/$1');
$routes->get('/api/board/nested-reply/get/([0-9]+)', '\App\Controllers\API\Board::getNestedReply/$1');
$routes->get('/api/profile', '\App\Controllers\API\Profile::index');
$routes->get('/api/admin/board/get/([0-9]+)', '\App\Controllers\API\Admin::getBoard/$1');
$routes->post('/api/admin/board/update/([0-9]+)', '\App\Controllers\API\Admin::updateBoard/$1');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
