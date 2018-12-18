<?php

Route::get('/test/{type}/{resourceId}', 'HandleInventoryController@show');

Route::get('/logistics', 'LogisticController@index');

Route::post('/orders/sync-all', 'OrderController@syncAll');
Route::post('/markets/sync-all', 'MarketController@syncAll');

Route::get('/product-variants', 'ProductVariantController@index');

// 供应商变体列表
Route::get('/suppliers/{supplier}/variants', 'SupplierController@variants');

Route::get('/warehouses/{warehouse}/variants', 'WarehouseController@variants');

/*
|--------------------------------------------------------------------------
| 入库API
|--------------------------------------------------------------------------
|
| InventoryIncome
|
*/
// 入库详情
Route::get('/inventory-incomes/{inventoryIncome}', 'InventoryIncomeController@show');
// 供应商入库审核
Route::put('/inventory-incomes/{inventoryIncome}/review', 'InventoryIncomeController@review');
// 供应商入库商品发货
Route::post('/inventory-incomes/{inventoryIncome}/shipment', 'InventoryIncomeController@shipment');
// 测试审核路由
Route::any('/inventory-incomes/{inventoryIncome}/approved', 'InventoryIncomeController@approved');
// 测试入库路由
Route::any('/inventory-incomes/{inventoryIncome}/put', 'InventoryIncomeController@put');
/*
|--------------------------------------------------------------------------
| 出库API
|--------------------------------------------------------------------------
|
| InventoryExpend
|
*/

// 出库详情
Route::get('/inventory-expends/{inventoryExpend}', 'InventoryExpendController@show');
// 出库审核
Route::put('/inventory-expends/{inventoryExpend}/review', 'InventoryExpendController@review');
// 出库商品发货
Route::post('/inventory-expends/{inventoryExpend}/shipment', 'InventoryExpendController@shipment');
// 测试出库审核路由
Route::any('/inventory-expends/{inventoryExpend}/approved', 'InventoryExpendController@approved');
// 测试出库路由
Route::any('/inventory-expends/{inventoryExpend}/take', 'InventoryExpendController@take');

Route::any('/inventory-expends/{inventoryExpend}/cancel', 'InventoryExpendController@cancel');


// 供应商报名参加活动
Route::get('/promotions', 'PromotionController@index');
// 获取所有活动类型
Route::get('/promotions/types', 'PromotionController@types');
// 获取活动详情
Route::get('/promotions/{promotion}', 'PromotionController@show');

// 供应商提交产品审核
Route::put('/products/{product}/review', 'ProductController@review');

Route::get('/product-attributes', 'ProductAttributeController@index')->name('product.attribute.index');

Route::get('/product-options', 'ProductOptionController@index')->name('product.option.index');

Route::post('/locales/{locale}', 'LocaleController@set')->name('locale.set');
