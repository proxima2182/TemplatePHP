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
$routes->setDefaultController('MainController');
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
$routes->get('/', [\Views\MainController::class, 'index']);
$routes->get('/get-session', [\Views\MainController::class, 'getSession']);
$routes->get('/set-session', [\Views\MainController::class, 'setSession']);
$routes->get('/profile', [\Views\ProfileController::class, 'index']);
$routes->get('/register', [\Views\RegisterController::class, 'index']);
$routes->get('/board/grid/([0-9]+)', [\Views\BoardController::class, 'getGridBoard']);
$routes->addRedirect('/board/grid', '/board/grid/1');
$routes->get('/board/table/([0-9]+)', [\Views\BoardController::class, 'getTableBoard']);
$routes->addRedirect('/board/table', '/board/table/1');
$routes->get('/topic/([0-9]+)', [\Views\BoardController::class, 'getTopic']);
$routes->get('/topic/([0-9]+)/edit', [\Views\BoardController::class, 'editTopic']);
$routes->get('/test/image/(:any)', [\API\ImageFile::class, 'getImage']);
//admin pages
$routes->get('/admin/board/([0-9]+)', [\Views\Admin\BoardController::class, 'index']);
$routes->addRedirect('/admin/board', '/admin/board/1');
$routes->get('/admin/board/([a-zA-Z][a-zA-Z0-9]*)/([0-9]+)', [\Views\Admin\BoardController::class, 'getBoard']);
$routes->addRedirect('/admin/board/([a-zA-Z][a-zA-Z0-9]*)', '/admin/board/$1/1');
$routes->get('/admin/topic/([0-9]+)', [\Views\Admin\BoardController::class, 'getTopic']);
$routes->get('/admin/topic/([0-9]+)/edit', [\Views\Admin\BoardController::class, 'editTopic']);
$routes->get('/admin/topic/reply/([0-9]+)', [\Views\Admin\BoardController::class, 'getReply']);
$routes->addRedirect('/admin/topic/reply', '/admin/topic/reply/1');

$routes->get('/admin/location/([0-9]+)', [\Views\Admin\LocationController::class, 'index']);
$routes->addRedirect('/admin/location', '/admin/location/1');

$routes->get('/admin/user/([0-9]+)', [\Views\Admin\UserController::class, 'index']);
$routes->addRedirect('/admin/user', '/admin/user/1');

$routes->get('/admin/setting/([0-9]+)', [\Views\Admin\SettingController::class, 'index']);
$routes->addRedirect('/admin/setting', '/admin/setting/1');

$routes->get('/admin/category/([0-9]+)', [\Views\Admin\CategoryController::class, 'index']);
$routes->addRedirect('/admin/category', '/admin/category/1');
$routes->get('/admin/category/([a-zA-Z][a-zA-Z0-9]*)/([0-9]+)', [\Views\Admin\CategoryController::class, 'getCategory']);
$routes->addRedirect('/admin/category/([a-zA-Z][a-zA-Z0-9]*)', '/admin/category/$1/1');

$routes->get('/admin/reservation-board/([0-9]+)', [\Views\Admin\ReservationController::class, 'index']);
$routes->addRedirect('/admin/reservation-board', '/admin/reservation-board/1');
$routes->get('/admin/reservation-board/([a-zA-Z][a-zA-Z0-9]*)/([0-9]+)', [\Views\Admin\ReservationController::class, 'getBoard']);
$routes->addRedirect('/admin/reservation-board/([a-zA-Z][a-zA-Z0-9]*)', '/admin/reservation-board/$1/1');
$routes->get('/admin/reservation/([0-9]+)', [\Views\Admin\ReservationController::class, 'getReservation']);


//api
$routes->post('/api/image-file/upload', [\API\ImageFile::class, 'upload']);

$routes->get('/api/user/get/profile', [\API\User::class, 'getProfile']);
$routes->get('/api/user/get/([0-9]+)', [\API\User::class, 'getUser']);

$routes->get('/api/board/get/([0-9]+)', [\API\Board::class, 'getBoard']);
$routes->post('/api/board/create', [\API\Board::class, 'createBoard']);
$routes->post('/api/board/update/([0-9]+)', [\API\Board::class, 'updateBoard']);
$routes->delete('/api/board/delete/([0-9]+)', [\API\Board::class, 'deleteBoard']);
$routes->get('/api/board/topic/get/([a-zA-Z][a-zA-Z0-9]*)', [\API\Board::class, 'getBoardTopic']);

$routes->get('/api/topic/get/([0-9]+)', [\API\Topic::class, 'getTopic']);
$routes->post('/api/topic/create', [\API\Topic::class, 'createTopic']);
$routes->post('/api/topic/update/([0-9]+)', [\API\Topic::class, 'updateTopic']);
$routes->delete('/api/topic/delete/([0-9]+)', [\API\Topic::class, 'deleteTopic']);
$routes->get('/api/topic/get/([0-9]+)/reply', [\API\Topic::class, 'getTopicReply']);
$routes->get('/api/topic/reply/get/([0-9]+)/nested', [\API\Topic::class, 'getNestedReply']);
$routes->get('/api/topic/reply/get/([0-9]+)', [\API\Topic::class, 'getReply']);
$routes->delete('/api/topic/reply/delete/([0-9]+)', [\API\Topic::class, 'deleteReply']);

$routes->get('/api/location/get/([0-9]+)', [\API\Location::class, 'getLocation']);
$routes->post('/api/location/create', [\API\Location::class, 'createLocation']);
$routes->post('/api/location/update/([0-9]+)', [\API\Location::class, 'updateLocation']);
$routes->delete('/api/location/delete/([0-9]+)', [\API\Location::class, 'deleteLocation']);

$routes->get('/api/setting/get/([0-9]+)', [\API\Setting::class, 'getSetting']);
$routes->post('/api/setting/create', [\API\Setting::class, 'createSetting']);
$routes->post('/api/setting/update/([0-9]+)', [\API\Setting::class, 'updateSetting']);
$routes->delete('/api/setting/delete/([0-9]+)', [\API\Setting::class, 'deleteSetting']);

$routes->get('/api/category/get/([0-9]+)', [\API\Category::class, 'getCategory']);
$routes->post('/api/category/create', [\API\Category::class, 'createCategory']);
$routes->post('/api/category/update/([0-9]+)', [\API\Category::class, 'updateCategory']);
$routes->delete('/api/category/delete/([0-9]+)', [\API\Category::class, 'deleteCategory']);
$routes->get('/api/category/path/get/([0-9]+)', [\API\Category::class, 'getCategoryPath']);
$routes->post('/api/category/path/create', [\API\Category::class, 'createCategoryPath']);
$routes->post('/api/category/path/update/([0-9]+)', [\API\Category::class, 'updateCategoryPath']);
$routes->delete('/api/category/path/delete/([0-9]+)', [\API\Category::class, 'deleteCategoryPath']);

$routes->get('/api/reservation-board/get/([0-9]+)', [\API\Reservation::class, 'getBoard']);
$routes->post('/api/reservation-board/create', [\API\Reservation::class, 'createBoard']);
$routes->post('/api/reservation-board/update/([0-9]+)', [\API\Reservation::class, 'updateBoard']);
$routes->delete('/api/reservation-board/delete/([0-9]+)', [\API\Reservation::class, 'deleteBoard']);

$routes->get('/api/reservation/get/([0-9]+)', [\API\Reservation::class, 'getReservation']);
$routes->post('/api/reservation/request', [\API\Reservation::class, 'requestReservation']);
$routes->post('/api/reservation/reject/([0-9]+)', [\API\Reservation::class, 'rejectReservation']);
$routes->post('/api/reservation/accept/([0-9]+)', [\API\Reservation::class, 'acceptReservation']);

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
