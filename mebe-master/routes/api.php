<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use Illuminate\Support\Facades\Route;

Route::post('login', 'API\UserController@login');


Route::middleware(['api.token'])->group(function() {
    Route::prefix('users')->name('users.')->group(function() {
        Route::post('/', 'API\UserController@register')->name('store');
        Route::put('/{user_id}', 'API\UserController@update')->name('update');
        Route::get('/{user_id}', 'API\UserController@details')->name('detail');
        Route::put('/children/{children_id}', 'API\UserController@updateChildren')->name('children.update');
        Route::post('/deleBaby', 'API\UserController@deleteChildren');
        Route::post('/children', 'API\UserController@createChild')->name('children.create');
    });
    /*
     * Categories Route
     */
    Route::prefix('categories')->name('categories.')->group(function() {
        Route::get('/show/{id}', 'API\CategoryController@show')->name('index');
        Route::post('/group', 'API\CategoryController@categoryByGroup')->name('group');
        Route::get('/list/{parent}', 'API\CategoryController@getListByParent')->name('list');
        Route::get('/{id}', 'API\CategoryController@detail')->name('detail');
        Route::post('', 'API\CategoryController@store')->name('store');
        Route::put('/{id}', 'API\CategoryController@update')->name('update');
        Route::delete('/{id}', 'API\CategoryController@delete')->name('delete');
        Route::get('/tree/group/{group}', 'API\CategoryController@tree')->name('tree.index');
        Route::get('/attribute/{category_id}', 'API\CategoryController@attributeByCategory')->name('attribute.index');
        Route::get('/product/{category_id}', 'API\CategoryController@productByCategory')->name('product.index');
    });

    Route::prefix('categoryGroup')->name('categoryGroup.')->group(function (){
        Route::get('/', 'API\CategoryController@categoryGroupList')->name('group.index');
    });


    /*
     * Posts Route
     */
    Route::prefix('posts')->name('post.')->group(function() {
        Route::get('/{category_id}', 'API\PostController@show')->name('index');
        Route::post('/detail', 'API\PostController@detail')->name('detail');
        Route::post('/', 'API\PostController@store')->name('store');
        Route::put('/{id}', 'API\PostController@update')->name('update');
        Route::delete('/{id}', 'API\PostController@delete')->name('delete');
        Route::post('/favourites', 'API\PostController@storeFavouritePost')->name('favourites.store');
        Route::get('/favourites/list', 'API\PostController@listFavourite')->name('favourites.list');
        Route::get('/related/type{id}', 'API\PostController@related')->name('related.index');
        Route::get('/banner/{type}', 'API\PostController@getBanner')->name('banner');
    });

    /*
     * Diary Route
     */
    Route::prefix('diaries')->name('diary.')->group(function () {
        Route::post('', 'API\PostController@createDiary')->name('diary.create');
        Route::get('/date/{user}', 'API\PostController@countDate')->name('diary.show');
        Route::put('/{id}', 'API\PostController@updateDiary')->name('diary.update');
        Route::get('/latestDetail/{id}', 'API\PostController@latestDetail')->name('diary.latest.detail');
        Route::delete('/{id}', 'API\PostController@deleteDiary')->name('diary.delete');
        Route::get('/home', 'API\PostController@getHotNewDiary')->name('diary.home');
        Route::get('/list/{type}', 'API\PostController@getDiaries')->name('diary.list');
        Route::post('/detail', 'API\PostController@getDiary')->name('diary.detail');
        Route::get('/check/{id}', 'API\PostController@checkCompletedDiary')->name('diary.check');
    });

    /*
     * Comment Route
     */
    Route::prefix('comments')->name('comments.')->group(function() {
        Route::get('/{post_id}/{user_id}', 'API\CommentController@listComments')->name('index');
        Route::post('', 'API\CommentController@create')->name('store');
        Route::put('/{id}', 'API\CommentController@update')->name('update');
        Route::post('/delete', 'API\CommentController@delete')->name('delete');
        Route::get('/reply/{parent_id}/{user_id}', 'API\CommentController@listReplyComments')->name('reply.index');
    });
    /*
     * Like Route
     */
    Route::post('likes', 'API\LikeController@store')->middleware(['throttle:600,5']);

    /*
     * Other api
     */
    Route::get('entities', 'API\PostController@getListEntity');
    Route::get('images/{id}', 'API\PostController@getImages');
    Route::delete('images/{id}','API\PostController@deleteImage');
    Route::put('images/{id}','API\PostController@updateImage');
    Route::post('images','API\PostController@uploadImage');

    /*
     * Cart
     */
    Route::get('carts', 'API\CartController@show');
    Route::post('carts', 'API\CartController@store');
    Route::get('carts/qty', 'API\CartController@getQtyCart');
    Route::delete('carts/{id}', 'API\CartController@delete');
    /*
     *  Order checkout
     */
    Route::prefix('checkout')->name('checkout.')->group(function() {
        Route::post('/', 'API\OrderController@preview');
        Route::post('/confirm', 'API\OrderController@store');
    });
    /*
     *  Order process
     */
    Route::prefix('order')->name('order.')->group(function() {
        Route::get('/listOrder/{status}', 'API\OrderController@orders');
        Route::post('/cancel', 'API\OrderController@cancel');
        Route::get('/details/{id}', 'API\OrderController@show');
    });

    /*
     * Shipping
     */
    Route::prefix('shipping')->name('shipping.')->group(function() {
        Route::post('/calculate', 'API\ShippingController@calculateShippingFee');
        Route::post('/createShipment', 'API\ShippingController@shipment');
        Route::post('/cancel', 'API\ShippingController@cancel');
    });
    /*
     * Product Route
     */
    Route::prefix('products')->name('products.')->group(function() {
        Route::get('', 'API\ProductController@show')->name('index');
        Route::get('/{product_id}', 'API\ProductController@detail')->name('detail');
        Route::get('/slug/{slug}', 'API\ProductController@productBySlug')->name('slug.detail');
        Route::get('/type/{product_type}', 'API\ProductController@productByType')->name('type.index');
        Route::get('/variant/{product_master_id}', 'API\ProductController@productVariantByMaster')->name('variant.index');
        Route::get('/related/{product_id}', 'API\ProductController@productRelated')->name('related.index');
    });


    /*
     * Attribute Route
     */
    Route::get('attributes', 'API\AttributeController@show');
    Route::get('attributes/{attribute_id}', 'API\AttributeController@detail');

    /*
     * Search Route
     */
    Route::get('search/{key}', 'API\SearchController@search');

    /*
     * Notification Setting
     */
    Route::post('notification/setting', 'API\NotificationController@setting');
});
