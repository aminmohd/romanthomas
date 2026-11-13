<?php

use CodeIgniter\Router\RouteCollection;

/**
 * CI4 routes migrated from application/config/routes.php (CI3).
 *
 * CI3 reserved: default_controller Main/login, 404_override empty, translate_uri_dashes false.
 * Auto-routing is off; explicit routes + segment wildcards mirror CI3 remap rules.
 *
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Main');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);

// -------------------------------------------------------------------------
// Default controller (CI3: $route['default_controller'] = 'Main/login')
// -------------------------------------------------------------------------
$routes->get('/', 'Main::login');
$routes->match(['get', 'post'], 'login', 'Main::login');
$routes->get('logout', 'Main::logout');

// CI3: main/(:any) -> Main/$1 (duplicate logout alias also defined)
$routes->get('main/logout', 'Main::logout');
$routes->match(['get', 'post'], 'main/(:segment)', 'Main::$1');

// -------------------------------------------------------------------------
// Products (CI3 constructor enforces session auth — see Filters.php)
// -------------------------------------------------------------------------
$routes->get('products', 'Products::index');
$routes->get('products/index', 'Products::index');
$routes->get('products/index/(:num)', 'Products::index/$1');
$routes->post('products/update_squence_order', 'Products::update_squence_order');
$routes->get('products/add_products_into_DB', 'Products::add_products_into_DB');
// CI3: products/(:any) -> Products/$1 (single-segment method names)
$routes->get('products/(:segment)', 'Products::$1');
$routes->post('products/(:segment)', 'Products::$1');

// -------------------------------------------------------------------------
// Categories
// -------------------------------------------------------------------------
$routes->get('categories', 'Categories::index');
$routes->get('categories/add_category_into_DB', 'Categories::add_category_into_DB');
$routes->get('categories/(:segment)', 'Categories::$1');
$routes->post('categories/(:segment)', 'Categories::$1');

// -------------------------------------------------------------------------
// Webhooks (no CI3 auth)
// -------------------------------------------------------------------------
$routes->post('webhooks', 'Webhooks::index');
$routes->post('webhooks/index', 'Webhooks::index');
$routes->match(['get', 'post'], 'webhooks/test_mail', 'Webhooks::test_mail');
$routes->match(['get', 'post'], 'webhooks/(:segment)', 'Webhooks::$1');

// -------------------------------------------------------------------------
// API — CI3 segment route api/get_customer (no entry in routes.php; auto URI)
// -------------------------------------------------------------------------
$routes->match(['get', 'post', 'options'], 'api/get_customer', 'Api::get_customer');

// -------------------------------------------------------------------------
// Welcome — CI3 default controller not used; controller reachable via URI
// -------------------------------------------------------------------------
$routes->get('welcome', 'Welcome::index');
$routes->get('welcome/index', 'Welcome::index');
$routes->match(['get', 'post'], 'welcome/(:segment)', 'Welcome::$1');
