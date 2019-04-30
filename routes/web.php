<?php

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

//用户登录注册
Auth::routes();

//登录
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

//重写注册为手机短信
Route::get('register', 'Auth\RegisterController@showPart1')->name('register');
Route::post('checkCaptcha', 'Auth\RegisterController@sendSms')->name('register.checkCaptcha');
Route::get('register2', 'Auth\RegisterController@showPart2')->name('register2');
Route::post('register3', 'Auth\RegisterController@showPart3')->name('register3');

//重写忘记密码为手机短信验证,并且保留原来的邮箱找回逻辑和路由
Route::prefix('sms')->group(function () {
    $this->get('password/sms', 'Auth\ForgotPasswordController@showSendSmsForm')
        ->name('sms.password.sms'); //显示填写手机号表单
    $this->post('password/check', 'Auth\ForgotPasswordController@sendResetSms')
        ->name('sms.password.check'); //对比验证码，发送手机短信
    $this->get('password/reset', 'Auth\ForgotPasswordController@showResetForm')
        ->name('sms.password.resetform'); //显示重置新密码表单
    $this->post('password/reset', 'Auth\ForgotPasswordController@smsResetPassword')
        ->name('sms.password.reset'); //更新用户新密码
});

Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products', 'ProductsController@index')->name('products.index');

//Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');
    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders', 'OrdersController@index')->name('orders.index');
    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
    Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');

    Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');
});
Route::get('products/{product}', 'ProductsController@show')->name('products.show');
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');
