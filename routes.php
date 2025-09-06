<?php

use App\Core\Router;
use App\Middleware\Admin;
use App\Middleware\Auth;
use App\Middleware\Guest;
use App\Middleware\Master;

Router::get('/', 'PageController@home');

Router::get('/proizvodi', 'ProductController@index');
Router::get('/proizvodi/{id}', "ProductController@show");

Router::get('/o-nama', 'PageController@o_nama');

Router::get('/kontakt', 'PageController@kontakt');

Router::get('/korpa', 'CartController@korpa');
Router::post('/korpa', 'CartController@add');
Router::delete('/korpa/remove/{id}', 'CartController@remove');
Router::post('/checkout', 'OrderController@create');


Router::get("/api/products", 'ProductController@getAll');

Router::post("/upload-image", "AdminController@uploadImage")->only(Admin::class);
Router::post("/delete-uploaded-image", "AdminController@deleteImage")->only(Admin::class);


Router::post('/admin/login', 'AuthController@login')->only(Guest::class);
Router::delete('/admin/logout', 'AuthController@logout')->only(Admin::class);

Router::get('/admin', 'AdminController@loginForm')->only(Guest::class);
Router::get('/admin/dashboard', 'AdminController@dashboard')->only(Admin::class);

Router::get('/admin/products', 'AdminProductController@index')->only(Admin::class);
Router::get('/admin/products/create', 'AdminProductController@create')->only(Admin::class);
Router::post('/admin/products', 'AdminProductController@store')->only(Admin::class);
Router::get('/admin/products/{id}/edit', 'AdminProductController@edit')->only(Admin::class);
Router::put('/admin/products/{id}', 'AdminProductController@update')->only(Admin::class);
Router::delete('/admin/products/{id}', 'AdminProductController@delete')->only(Admin::class);

Router::get('/admin/users', 'AdminUserController@index')->only(Master::class);
Router::get('/admin/users/create', 'AdminUserController@create')->only(Master::class);
Router::post('/admin/users', 'AdminUserController@store')->only(Master::class);
Router::get('/admin/users/{id}/edit', 'AdminUserController@edit')->only(Master::class);
Router::put('/admin/users/{id}', 'AdminUserController@update')->only(Master::class);
Router::delete('/admin/users/{id}', 'AdminUserController@destroy')->only(Master::class);

// Router::get("/admin/orders", "admin/orders/index.php")->only("admin");
// Router::get('/admin/orders/all', 'admin/orders/index.php')->only("admin");
// Router::get('/admin/orders/finished', 'admin/orders/index.php')->only("admin");
// Router::get('/admin/orders/unfinished', 'admin/orders/index.php')->only("admin");

// Router::patch("/admin/orders/finish", "admin/orders/finish.php")->only("admin");

// Router::get("/admin/products/create", "admin/products/create.php")->only("admin");


// Router::get('/admin/login', 'AdminAuthController@showLoginForm')->only(AdminGuestMiddleware::class);
// Router::post('/admin/login', 'AdminAuthController@login')->only(AdminGuestMiddleware::class);
// Router::post('/admin/logout', 'AdminAuthController@logout')->only(AdminAuthMiddleware::class);

// // Dashboard
// Router::get('/admin/dashboard', 'AdminDashboardController@index')->only(AdminAuthMiddleware::class);


// // Admin users


// // Orders
// Router::get('/admin/orders', 'AdminOrderController@index')->only(AdminAuthMiddleware::class);
// Router::get('/admin/orders/{id}', 'AdminOrderController@show')->only(AdminAuthMiddleware::class);
// Router::put('/admin/orders/{id}', 'AdminOrderController@update')->only(AdminAuthMiddleware::class);
