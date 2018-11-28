<?php

Route::get('/test/{type}/{resourceId}', 'HandleInventoryController@show');

Route::get('/logistics', 'LogisticController@index');

Route::post('/orders/sync-all', 'OrderController@syncAll');
Route::post('/markets/sync-all', 'MarketController@syncAll');

Route::put('/inventory-incomes/{inventoryIncome}/review', 'InventoryIncomeController@review');