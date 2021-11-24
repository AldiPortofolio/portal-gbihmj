<?php
//Main route
Route::any('/', 'IndexController@index');

/*Booking*/
Route::get('get_booking/{id}', 'BookingController@get_booking');
