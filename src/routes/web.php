<?php

Route::post('/social-login', 'pimplesushant\laravelsocialiteapi\SocialController@social');
Route::get('/test', function(){
	echo 'Hello from the Social Login package!';
});