<?php

// Route::get('booting', 'IndexController@booting');
Route::get('test2', 'HomeController@test');
Route::get('get_youtubeVideoData', 'HomeController@get_youtubeVideoData');
// Route::get('updateOrderStatus/{id}', 'IndexController@updateOrderStatus');

Route::group(['prefix' => 'account'], function(){
	Route::post('register', 'LoginController@register');
	Route::post('check_no_kaj', 'LoginController@check_no_kaj');
	Route::post('request_otp', 'LoginController@request_otp');
	Route::post('verification_otp', 'LoginController@verification_otp');

	Route::post('check_phone', 'LoginController@check_phone');
	Route::post('logout', 'LoginController@logout');
	Route::post('check_otp', 'LoginController@check_otp');

	Route::get('send_email/{email}', 'LoginController@send_email');
	// Route::get('forgot_password/{token}', 'LoginController@forgot_password');
	// Route::post('reset', 'LoginController@reset');

	Route::get('send_push_notif_birthday', 'LoginController@send_push_notif_birthday');
});

Route::group(['prefix' => 'home'], function(){
	Route::post('home', 'HomeController@home');
	Route::get('event/{id}', 'HomeController@event');
	Route::get('get_available_seat/{event_id}/{time}', 'HomeController@get_available_seat');
	Route::post('booking', 'HomeController@booking');
	Route::get('get_booking/{id}', 'HomeController@get_booking');
	Route::get('get_articles/{offset}/{limit}', 'HomeController@get_articles');
	Route::get('get_channels/{offset}/{limit}', 'HomeController@get_channels');

	Route::get('get_events/{offset}/{limit}', 'HomeController@get_events');
	Route::get('get_event_category', 'HomeController@get_event_category');
	Route::post('config', 'HomeController@config');
});


Route::group(['prefix' => 'booking'], function(){
	Route::get('get_bookings/{offset}/{limit}', 'BookingController@get_bookings');
	Route::get('get_past_bookings', 'BookingController@get_past_bookings');

	Route::post('edit_booking', 'BookingController@edit_booking');
});


Route::group(['prefix' => 'profile'], function(){
	Route::get('get_user', 'ProfileController@get_user');
	Route::post('edit_user_setting', 'ProfileController@edit_user_setting');
	Route::post('delete_user', 'ProfileController@delete_user');
	Route::post('upload_profile_image', 'ProfileController@upload_profile_image');

	Route::get('delete_profile_image', 'ProfileController@delete_profile_image');
	Route::post('edit_profile', 'ProfileController@edit_profile');
});

Route::group(['prefix' => 'music'], function(){
	Route::get('get_music', 'MusicController@get_music');
});

Route::group(['prefix' => 'warta'], function(){
	Route::get('get_warta', 'WartaController@get_warta');

	Route::post('upload_file', 'WartaController@upload_file');
});

Route::group(['prefix' => 'church'], function(){
	Route::get('edit_status_church_image', 'ChurchController@edit_status_church_image');
	Route::post('delete_church_image', 'ChurchController@delete_church_image');
	Route::any('upload_image', 'ChurchController@upload_image');
});