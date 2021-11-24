<?php
	Route::get('login','LoginController@index');
	Route::post('cek_login','LoginController@cek_login');
	Route::post('forgot_password','LoginController@forgot_password');
	Route::get('logout','LoginController@logout');
	
	Route::get('profile', 'LoginController@profile');
	Route::post('update_profile', 'LoginController@update_profile');
	Route::get('change-language/{id}','LoginController@change_language');
	Route::get('thanks_page','LoginController@thanks_page');

	//Main route
	Route::get('/', 'IndexController@index');

	Route::group(['prefix' => 'administration'], function(){
		Route::resource('user', 'UserController');
		Route::any('user/ext/{action}','UserController@ext');
		Route::get('user/get_store/{id}','UserController@get_store');

		Route::resource('access-right', 'UseraccessController');
		Route::any('access-right/ext/{action}','UseraccessController@ext');

		Route::resource('user-access', 'UseraccessController');
		Route::any('user-access/ext/{action}','UseraccessController@ext');
	});

	Route::group(['prefix' => 'booking'], function(){
		Route::resource('manage-booking', 'BookingController');
		Route::any('manage-booking/ext/{action}','BookingController@ext');
	});

	Route::group(['prefix' => 'content'], function(){
		Route::resource('article', 'ArticleController');
		Route::any('article/ext/{action}','ArticleController@ext');

		Route::resource('warta', 'WartaController');
		Route::any('warta/ext/{action}','WartaController@ext');

		Route::resource('music', 'MusicController');
		Route::any('music/ext/{action}','MusicController@ext');

		Route::resource('channel', 'ChannelController');
		Route::any('channel/ext/{action}','ChannelController@ext');
	});

	Route::group(['prefix' => 'church'], function(){
		Route::resource('manage-church', 'ChurchController');
		Route::any('manage-church/ext/{action}','ChurchController@ext');

		Route::resource('room', 'RoomController');
		Route::any('room/ext/{action}','RoomController@ext');

		Route::resource('event', 'EventController');
		Route::any('event/ext/{action}','EventController@ext');

		// Route::post('manage-church/upload_image', 'ChurchController@upload_image');
		// Route::get('manage-church/edit_status_church_image', 'ChurchController@edit_status_church_image');
		// Route::post('manage-church/delete_church_image', 'ChurchController@delete_church_image');
	});

	// Route::group(['prefix' => 'outlets'], function(){
	// 	Route::resource('manage-outlet', 'OutletController');
	// 	Route::any('manage-outlet/ext/{action}','OutletController@ext');
	// });

	// Route::group(['prefix' => 'modem'], function(){
	// 	Route::resource('manage-modem', 'ModemController');
	// 	Route::any('manage-modem/ext/{action}','ModemController@ext');

	// 	Route::resource('category-modem', 'CategoryModemController');
	// 	Route::any('category-modem/ext/{action}','CategoryModemController@ext');

	// 	Route::resource('promo', 'PromoController');
	// 	Route::any('promo/ext/{action}','PromoController@ext');
	// });

	// Route::group(['prefix' => 'account-&-payment'], function(){
	// 	Route::resource('bank-account', 'BankAccountController');
	// 	Route::any('bank-account/ext/{action}','BankAccountController@ext');
	// });

	// Route::group(['prefix' => 'news'], function(){
	// 	Route::resource('manage-news', 'NewsController');
	// 	Route::any('manage-news/ext/{action}','NewsController@ext');
	// });

	// Route::group(['prefix' => 'inbox'], function(){
	// 	Route::resource('manage-inbox', 'InboxController');
	// 	Route::get('manage-inbox/{sender}/{id}', 'InboxController@detail');
	// });

	// Route::group(['prefix' => 'mobile'], function(){
	// 	Route::resource('banner', 'BannerController');
	// 	Route::any('banner/{ext}/{action}','BannerController@ext');

	// 	// Route::resource('merchant-category', 'MerchantCategoryController');
	// 	// Route::any('merchant-category/ext/{action}','MerchantCategoryController@ext');

	// 	// Route::get('manage-merchant/get_merchant_category/{par}', 'MerchantController@get_merchant_category');
	// 	// Route::get('manage-merchant/get_merchant_location/{par}', 'MerchantController@get_merchant_location');
	// });

	// Route::group(['prefix' => 'post'], function(){
	// 	Route::resource('manage-post', 'PostController');
	// 	Route::any('manage-post/ext/{action}','PostController@ext');

	// 	Route::resource('orders-customer', 'ReportCustomerController');
	// 	Route::any('orders-customer/ext/{action}','ReportCustomerController@ext');
	// });

	// Route::group(['prefix' => 'report'], function(){
	// 	Route::resource('stock-report', 'StockReportController');
	// 	Route::any('stock-report/ext/{action}','StockReportController@ext');

	// 	Route::resource('outlet-income-report', 'OutletIncomeReportController');
	// 	Route::any('outlet-income-report/ext/{action}','OutletIncomeReportController@ext');

	// 	Route::resource('omzet-report', 'OmzetReportController');
	// 	Route::any('omzet-report/ext/{action}','OmzetReportController@ext');
	// });


	// 	Route::resource('user', 'UserController');
	// 	Route::any('user/ext/{action}','UserController@ext');

	// 	Route::resource('customer', 'CustomerController');
	// 	Route::any('customer/ext/{action}','CustomerController@ext');
	// 	Route::get('customer/posting/{id}/{slug}', 'CustomerController@detail');
	// });

	Route::group(['prefix' => 'preferences'], function(){
		Route::get('general-settings', 'ConfigController@index');
		Route::post('general-settings/update', 'ConfigController@update');
	});

	// Route::group(['prefix' => 'location'], function(){
	// 	Route::resource('province', 'ProvinceController');
	// 	Route::any('province/ext/{action}','ProvinceController@ext');

	// 	Route::resource('city', 'CityController');
	// 	Route::any('city/ext/{action}','CityController@ext');

	// 	Route::resource('district', 'DistrictController');
	// 	Route::any('district/ext/{action}','DistrictController@ext');

	// 	Route::resource('subdistrict', 'SubdistrictController');
	// 	Route::any('subdistrict/ext/{action}','SubdistrictController@ext');

	// 	Route::resource('country', 'CountryController');
	// 	Route::any('country/ext/{action}','CountryController@ext');

	// });

	// Route::group(['prefix' => 'shipping'], function(){
	// 	Route::resource('city-ongkir', 'CityOngkirController');
	// 	Route::any('city-ongkir/ext/{action}','CityOngkirController@ext');

	// 	// Route::any('getongkir/{from}/{dest}/{weight}','OrderController@getongkir');
	// });

	// Route::group(['prefix' => 'notification'], function(){
	// 	Route::resource('manage-notification', 'NotificationController');
	// 	Route::any('manage-notification/ext/{action}','NotificationController@ext');
	// });

	//Main services for ajax outside builder
	Route::any('services','ServicesController@index');