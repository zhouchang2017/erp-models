<?php

Route::get('/test/{type}/{resourceId}', 'HandleInventoryController@show');

Route::get('/logistics', 'LogisticController@index');

Route::post('/orders/sync-all', 'OrderController@syncAll');
Route::post('/markets/sync-all', 'MarketController@syncAll');

Route::get('/inventory-incomes/{inventoryIncome}', 'InventoryIncomeController@show');

Route::put('/inventory-incomes/{inventoryIncome}/review', 'InventoryIncomeController@review');

Route::get('/product-attributes', 'ProductAttributeController@index')->name('product.attribute.index');

Route::get('/product-options', 'ProductOptionController@index')->name('product.option.index');

Route::post('/locales/{locale}', 'LocaleController@set')->name('locale.set');
