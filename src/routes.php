<?php

Route::post('/social-login', 'SocialController@social');
Route::get('/test', function(){
	echo 'Hello from the Social Login package!';
});