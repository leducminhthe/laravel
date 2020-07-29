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
//Frontend
Route::get('/', 'HomeController@index');
Route::get('trangchu',[
	'as'=>'trangchu',
	'uses'=>'HomeController@index'
]);
Route::post('tim-kiem','HomeController@search');

//danh mục  sản phẩm
Route::get('danh-muc-san-pham/{category_id}','CategoryProduct@show_category_home');
//thương hiệu sản phẩm
Route::get('thuong-hieu-san-pham/{brand_id}','BrandProduct@show_brand_home');
//chi tiết sản phẩm
Route::get('chi-tiet-san-pham/{product_id}','ProductController@details_product');

//Admin
Route::get('admin', 'AdminController@index');
Route::get('dashboard', 'AdminController@show_dashboard');
Route::post('admin-dashboard', 'AdminController@dashboard');
Route::get('logout','AdminController@logout');

//CategoryProdcut
Route::get('add-category-product', 'CategoryProduct@add_category_product');
Route::get('all-category-product', 'CategoryProduct@all_category_product');
Route::post('save-category-product', 'CategoryProduct@save_category_product');

Route::get('unactive-category-product/{category_product_id}', 'CategoryProduct@unactive_category_product');
Route::get('active-category-product/{category_product_id}', 'CategoryProduct@active_category_product');

Route::get('edit-category-product/{category_product_id}', 'CategoryProduct@edit_category_product');
Route::get('delete-category-product/{category_product_id}', 'CategoryProduct@delete_category_product');
Route::post('update-category-product/{category_product_id}', 'CategoryProduct@update_category_product');

//BrandProduct
Route::get('add-brand-product', 'BrandProduct@add_brand_product');
Route::get('all-brand-product', 'BrandProduct@all_brand_product');
Route::post('save-brand-product', 'BrandProduct@save_brand_product');

Route::get('unactive-brand-product/{brand_product_id}', 'BrandProduct@unactive_brand_product');
Route::get('active-brand-product/{brand_product_id}', 'BrandProduct@active_brand_product');

Route::get('edit-brand-product/{brand_product_id}', 'BrandProduct@edit_brand_product');
Route::get('delete-brand-product/{brand_product_id}', 'BrandProduct@delete_brand_product');
Route::post('update-brand-product/{brand_product_id}', 'BrandProduct@update_brand_product');

//Product
Route::get('add-product', 'ProductController@add_product');
Route::get('all-product', 'ProductController@all_product');
Route::post('save-product', 'ProductController@save_product');

Route::get('unactive-product/{product_id}', 'ProductController@unactive_product');
Route::get('active-product/{product_id}', 'ProductController@active_product');

Route::get('edit-product/{product_id}', 'ProductController@edit_product');
Route::get('delete-product/{product_id}', 'ProductController@delete_product');
Route::post('update-product/{product_id}', 'ProductController@update_product');

//Cart
Route::post('save-cart','CartController@save_cart');
Route::get('show-cart','CartController@show_cart');
Route::get('delete-to-cart/{rowId}','CartController@delete_cart');
Route::post('update-to-cart','CartController@update_cart');
Route::get('delete-all-cart','CartController@delete_all_cart');

//Checkout
Route::get('login-checkout','CheckoutController@login_checkout');
Route::get('logout-checkout','CheckoutController@logout_checkout');
Route::post('add-customer','CheckoutController@add_customer');
Route::post('login-customer','CheckoutController@login_customer');
Route::get('checkout','CheckoutController@checkout');
Route::get('payment','CheckoutController@payment');
Route::post('save-checkout-customer','CheckoutController@save_checkout_customer');
Route::post('order-pay','CheckoutController@order_pay');

//đon hàng
Route::get('manage-order','CheckoutController@manage_order');
Route::get('view-order/{orderId}','CheckoutController@view_order');

//send mail
Route::get('send-mail','HomeController@send_mail');

//login with facebook
Route::get('login-facebook','AdminController@login_facebook');
Route::get('admin/callback','AdminController@callback_facebook');



