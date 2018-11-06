<?php

Route::get('/test/{type}/{resourceId}', 'HandleInventoryController@show');

Route::get('/logistics', 'LogisticController@index');