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
$routes->get('/register', 'RegisterController::index');
$routes->get('/board/grid(/([0-9]+))*', 'BoardController::getGridBoard/$1');
$routes->get('/board/table(/([0-9]+))*', 'BoardController::getTableBoard/$1');
$routes->get('/topic/([0-9]+)', 'BoardController::getTopic/$1');
$routes->get('/topic/[0-9]+/edit', 'BoardController::editTopic/$1');
$routes->get('/test/image/(:any)', 'API\ImageFile::getImage/$1');
//admin pages
$routes->get('/admin/board(/([0-9]+))*', 'AdminController::getBoards/$1');
$routes->get('/admin/board/([a-zA-Z][a-zA-Z0-9]*)(/([0-9]+))*', 'AdminController::getBoard/$1/$2');
$routes->get('/admin/topic/([0-9]+)', 'AdminController::getTopic/$1');
$routes->get('/admin/topic/[0-9]+/edit', 'AdminController::editTopic/$1');
$routes->get('/admin/topic/reply(/[0-9]+)*', 'AdminController::getReply/$1');
$routes->get('/admin/location(/[0-9]+)*', 'AdminController::getLocation/$1');
$routes->get('/admin/user(/[0-9]+)*', 'AdminController::getUser/$1');
$routes->get('/admin/setting(/[0-9]+)*', 'AdminController::getSetting/$1');
$routes->get('/admin/category(/[0-9]+)*', 'AdminController::getCategories/$1');
$routes->get('/admin/category/([a-zA-Z][a-zA-Z0-9]*)(/([0-9]+))*', 'AdminController::getCategory/$1/$2');
$routes->get('/admin/reservation-board(/([0-9]+))*', 'AdminController::getReservationBoards/$1');
$routes->get('/admin/reservation-board/([a-zA-Z][a-zA-Z0-9]*)(/([0-9]+))*', 'AdminController::getReservationTable/$1/$2');
$routes->get('/admin/reservation/([0-9]+)', 'AdminController::getReservation/$1');


//api
$routes->post('/api/image-file/upload', 'API\ImageFile::upload/$1');

$routes->get('/api/profile', 'API\Profile::index');

$routes->get('/api/user/get/([0-9]+)', 'API\User::getUser/$1');

$routes->get('/api/topic/get/([0-9]+)', 'API\Topic::getTopic/$1');
$routes->post('/api/topic/create', 'API\Topic::createTopic');
$routes->post('/api/topic/update/([0-9]+)', 'API\Topic::updateTopic/$1');
$routes->delete('/api/topic/delete/([0-9]+)', 'API\Topic::deleteTopic/$1');

$routes->get('/api/topic/get/([0-9]+)/reply', 'API\Topic::getTopicReply/$1');
$routes->get('/api/topic/reply/get/([0-9]+)/nested', 'API\Topic::getNestedReply/$1');
$routes->get('/api/topic/reply/get/([0-9]+)', 'API\Topic::getReply/$1');
$routes->delete('/api/topic/reply/delete/([0-9]+)', 'API\Topic::deleteReply/$1');

$routes->get('/api/board/get/([0-9]+)', 'API\Board::getBoard/$1');
$routes->post('/api/board/create', 'API\Board::createBoard');
$routes->post('/api/board/update/([0-9]+)', 'API\Board::updateBoard/$1');
$routes->delete('/api/board/delete/([0-9]+)', 'API\Board::deleteBoard/$1');

$routes->get('/api/board/topic/get/([a-zA-Z][a-zA-Z0-9]*)', 'API\Board::getBoardTopic/$1');

$routes->get('/api/location/get/([0-9]+)', 'API\Location::getLocation/$1');
$routes->post('/api/location/create', 'API\Location::createLocation');
$routes->post('/api/location/update/([0-9]+)', 'API\Location::updateLocation/$1');
$routes->delete('/api/location/delete/([0-9]+)', 'API\Location::deleteLocation/$1');

$routes->get('/api/setting/get/([0-9]+)', 'API\Setting::getSetting/$1');
$routes->post('/api/setting/create', 'API\Setting::createSetting');
$routes->post('/api/setting/update/([0-9]+)', 'API\Setting::updateSetting/$1');
$routes->delete('/api/setting/delete/([0-9]+)', 'API\Setting::deleteSetting/$1');

$routes->get('/api/category/get/([0-9]+)', 'API\Category::getCategory/$1');
$routes->post('/api/category/create', 'API\Category::createCategory');
$routes->post('/api/category/update/([0-9]+)', 'API\Category::updateCategory/$1');
$routes->delete('/api/category/delete/([0-9]+)', 'API\Category::deleteCategory/$1');

$routes->get('/api/category/path/get/([0-9]+)', 'API\Category::getCategoryPath/$1');
$routes->post('/api/category/path/create', 'API\Category::createCategoryPath');
$routes->post('/api/category/path/update/([0-9]+)', 'API\Category::updateCategoryPath/$1');
$routes->delete('/api/category/path/delete/([0-9]+)', 'API\Category::deleteCategoryPath/$1');

$routes->get('/api/reservation-board/get/([0-9]+)', 'API\Reservation::getBoard/$1');
$routes->post('/api/reservation-board/create', 'API\Reservation::createBoard');
$routes->post('/api/reservation-board/update/([0-9]+)', 'API\Reservation::updateBoard/$1');
$routes->delete('/api/reservation-board/delete/([0-9]+)', 'API\Reservation::deleteBoard/$1');

$routes->get('/api/reservation/get/([0-9]+)', 'API\Reservation::getReservation/$1');
$routes->post('/api/reservation/request', 'API\Reservation::requestReservation');
$routes->post('/api/reservation/reject/([0-9]+)', 'API\Reservation::rejectReservation/$1');
$routes->post('/api/reservation/accept/([0-9]+)', 'API\Reservation::acceptReservation/$1');

$routes->post('/api/email/send', 'API\Email::send');

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
