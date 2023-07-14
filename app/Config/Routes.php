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
$routes->get('/board/grid(/([0-9]+))*', 'BoardController::getGridBoard/$1');
$routes->get('/board/table(/([0-9]+))*', 'BoardController::getTableBoard/$1');
$routes->get('/topic/([0-9]+)', 'BoardController::getTopic/$1');
$routes->get('/topic/[0-9]+/edit', 'BoardController::editTopic/$1');
$routes->get('/test/image/(:any)', 'API\ImageFile::getImage/$1');
//admin pages
$routes->get('/admin/boards(/([0-9]+))*', 'AdminController::getBoards/$1');
$routes->get('/admin/boards/([a-zA-Z]+)(/([0-9]+))*', 'AdminController::getBoard/$1/$2');
$routes->get('/admin/topic/([0-9]+)', 'AdminController::getTopic/$1');
$routes->get('/admin/topic/[0-9]+/edit', 'AdminController::editTopic/$1');
$routes->get('/admin/topic/reply(/[0-9]+)*', 'AdminController::getReply/$1');


//api
$routes->post('/api/topic/create', 'API\Topic::createTopic');
$routes->post('/api/topic/update/([0-9]+)', 'API\Topic::updateTopic/$1');
$routes->get('/api/topic/get/([0-9]+)', 'API\Topic::getTopic/$1');
$routes->delete('/api/topic/delete/([0-9]+)', 'API\Topic::deleteTopic/$1');
$routes->get('/api/topic/get/([0-9]+)/reply', 'API\Topic::getTopicReply/$1');
$routes->get('/api/topic/reply/get/([0-9]+)/nested', 'API\Topic::getNestedReply/$1');
$routes->get('/api/topic/reply/get/([0-9]+)', 'API\Topic::getReply/$1');
$routes->get('/api/profile', 'API\Profile::index');
$routes->get('/api/board/get/([0-9]+)', 'API\Board::getBoard/$1');
$routes->post('/api/board/update/([0-9]+)', 'API\Board::updateBoard/$1');
$routes->post('/api/image-file/upload', 'API\ImageFile::upload/$1');


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
