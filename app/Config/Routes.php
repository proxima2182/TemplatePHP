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
/**
 * View Routes
 */
$routes->get('/', [\Views\MainController::class, 'index']);
$routes->get('/frame-view', [\Views\MainController::class, 'getFrameView']);
$routes->get('/get-session', [\Views\MainController::class, 'getSession']);
$routes->get('/set-session', [\Views\MainController::class, 'setSession']);
$routes->get('/profile', [\Views\ProfileController::class, 'index']);
$routes->get('/register', [\Views\RegisterController::class, 'index']);
$routes->get('/board/grid/([0-9]+)', [\Views\BoardController::class, 'getGridBoard']);
$routes->addRedirect('/board/grid', '/board/grid/1');
$routes->get('/board/table/([0-9]+)', [\Views\BoardController::class, 'getTableBoard']);
$routes->addRedirect('/board/table', '/board/table/1');
$routes->get('/board/([a-zA-Z][a-zA-Z0-9\-\_]*)/topic/create', [\Views\BoardController::class, 'createTopic']);
$routes->get('/topic/([0-9]+)', [\Views\BoardController::class, 'getTopic']);
$routes->get('/topic/([0-9]+)/edit', [\Views\BoardController::class, 'editTopic']);
//admin pages
$routes->get('/admin/board/([0-9]+)', [\Views\Admin\BoardController::class, 'index']);
$routes->addRedirect('/admin/board', '/admin/board/1');
$routes->get('/admin/board/([a-zA-Z][a-zA-Z0-9\-\_]*)/([0-9]+)', [\Views\Admin\BoardController::class, 'getBoard']);
$routes->addRedirect('/admin/board/([a-zA-Z][a-zA-Z0-9\-\_]*)', '/admin/board/$1/1');
$routes->get('/admin/board/([a-zA-Z][a-zA-Z0-9\-\_]*)/topic/create', [\Views\Admin\BoardController::class, 'createTopic']);
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

$routes->get('/admin/category', [\Views\Admin\CategoryController::class, 'index']);
$routes->get('/admin/category/([a-zA-Z][a-zA-Z0-9\-\_]*)', [\Views\Admin\CategoryController::class, 'getCategory']);

$routes->get('/admin/reservation-board/([0-9]+)', [\Views\Admin\ReservationController::class, 'index']);
$routes->addRedirect('/admin/reservation-board', '/admin/reservation-board/1');
$routes->get('/admin/reservation-board/([a-zA-Z][a-zA-Z0-9\-\_]*)/([0-9]+)', [\Views\Admin\ReservationController::class, 'getBoard']);
$routes->addRedirect('/admin/reservation-board/([a-zA-Z][a-zA-Z0-9\-\_]*)', '/admin/reservation-board/$1/1');
$routes->get('/admin/reservation/([0-9]+)', [\Views\Admin\ReservationController::class, 'getReservation']);


/**
 * API Routes
 */
$routes->post('/api/image-file/upload', [\API\ImageFileController::class, 'upload']);
$routes->post('/api/image-file/upload/([a-zA-Z0-9\-\_]*)', [\API\ImageFileController::class, 'upload']);
$routes->post('/api/image-file/refresh/([a-zA-Z0-9\-\_]*)', [\API\ImageFileController::class, 'refresh']);
$routes->delete('/api/image-file/delete/([0-9]+)', [\API\ImageFileController::class, 'delete']);
$routes->get('/image-file/(:any)', [\API\ImageFileController::class, 'getImage']);

$routes->get('/api/user/get/profile', [\API\UserController::class, 'getProfile']);
$routes->get('/api/user/get/([0-9]+)', [\API\UserController::class, 'getUser']);

$routes->get('/api/board/get/([0-9]+)', [\API\BoardController::class, 'getBoard']);
$routes->post('/api/board/create', [\API\BoardController::class, 'createBoard']);
$routes->post('/api/board/update/([0-9]+)', [\API\BoardController::class, 'updateBoard']);
$routes->delete('/api/board/delete/([0-9]+)', [\API\BoardController::class, 'deleteBoard']);
$routes->get('/api/board/topic/get/([a-zA-Z][a-zA-Z0-9\-\_]*)', [\API\BoardController::class, 'getStaticBoardTopics']);

$routes->get('/api/topic/get/([0-9]+)', [\API\TopicController::class, 'getTopic']);
$routes->post('/api/topic/create', [\API\TopicController::class, 'createTopic']);
$routes->post('/api/topic/update/([0-9]+)', [\API\TopicController::class, 'updateTopic']);
$routes->delete('/api/topic/delete/([0-9]+)', [\API\TopicController::class, 'deleteTopic']);
$routes->get('/api/topic/get/([0-9]+)/reply', [\API\TopicController::class, 'getTopicReply']);
$routes->get('/api/topic/reply/get/([0-9]+)/nested', [\API\TopicController::class, 'getNestedReply']);
$routes->get('/api/topic/reply/get/([0-9]+)', [\API\TopicController::class, 'getReply']);
$routes->delete('/api/topic/reply/delete/([0-9]+)', [\API\TopicController::class, 'deleteReply']);

$routes->get('/api/location/get/([0-9]+)', [\API\LocationController::class, 'getLocation']);
$routes->post('/api/location/create', [\API\LocationController::class, 'createLocation']);
$routes->post('/api/location/update/([0-9]+)', [\API\LocationController::class, 'updateLocation']);
$routes->delete('/api/location/delete/([0-9]+)', [\API\LocationController::class, 'deleteLocation']);

$routes->get('/api/setting/get/([0-9]+)', [\API\SettingController::class, 'getSetting']);
$routes->post('/api/setting/create', [\API\SettingController::class, 'createSetting']);
$routes->post('/api/setting/update/([0-9]+)', [\API\SettingController::class, 'updateSetting']);
$routes->delete('/api/setting/delete/([0-9]+)', [\API\SettingController::class, 'deleteSetting']);

$routes->get('/api/category/get/all', [\API\CategoryController::class, 'getCategoryAll']);
$routes->get('/api/category/get/([0-9]+)', [\API\CategoryController::class, 'getCategory']);
$routes->post('/api/category/create', [\API\CategoryController::class, 'createCategory']);
$routes->post('/api/category/update/([0-9]+)', [\API\CategoryController::class, 'updateCategory']);
$routes->delete('/api/category/delete/([0-9]+)', [\API\CategoryController::class, 'deleteCategory']);
$routes->get('/api/category/exchange-priority/([0-9]+)/([0-9]+)', [\API\CategoryController::class, 'exchangeCategoryPriority']);
$routes->get('/api/category/path/get/([0-9]+)', [\API\CategoryController::class, 'getCategoryPath']);
$routes->post('/api/category/path/create', [\API\CategoryController::class, 'createCategoryPath']);
$routes->post('/api/category/path/update/([0-9]+)', [\API\CategoryController::class, 'updateCategoryPath']);
$routes->delete('/api/category/path/delete/([0-9]+)', [\API\CategoryController::class, 'deleteCategoryPath']);
$routes->get('/api/category/path/exchange-priority/([0-9]+)/([0-9]+)', [\API\CategoryController::class, 'exchangeCategoryPathPriority']);

$routes->get('/api/reservation-board/get/([0-9]+)', [\API\ReservationController::class, 'getBoard']);
$routes->post('/api/reservation-board/create', [\API\ReservationController::class, 'createBoard']);
$routes->post('/api/reservation-board/update/([0-9]+)', [\API\ReservationController::class, 'updateBoard']);
$routes->delete('/api/reservation-board/delete/([0-9]+)', [\API\ReservationController::class, 'deleteBoard']);

$routes->get('/api/reservation/get/([0-9]+)', [\API\ReservationController::class, 'getReservation']);
$routes->post('/api/reservation/request', [\API\ReservationController::class, 'requestReservation']);
$routes->post('/api/reservation/reject/([0-9]+)', [\API\ReservationController::class, 'rejectReservation']);
$routes->post('/api/reservation/accept/([0-9]+)', [\API\ReservationController::class, 'acceptReservation']);

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
