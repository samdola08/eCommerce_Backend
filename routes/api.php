<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CountryApiController;
use App\Http\Controllers\API\Inventory\BrandApiController;
use App\Http\Controllers\API\Inventory\CategoryApiController;
use App\Http\Controllers\API\Inventory\ProductApiController;
use App\Http\Controllers\API\Supplier\SupplierApiController;
use App\Http\Controllers\API\WareHouse\WarehouseApiController;
use App\Http\Controllers\API\Purchase\PurchaseApiController;
use App\Http\Controllers\API\Customer\CustomerApiController;
use App\Http\Controllers\API\Order\OrderApiController;
use App\Http\Controllers\API\Stock\StockApiController;
use App\Http\Controllers\API\Order\OrderDeliveryApiController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::apiResources([
    'countries' =>  CountryApiController::class,
    'brands' => BrandApiController::class,
    'categories' => CategoryApiController::class,
    'suppliers' => SupplierApiController::class,
    'warehouses' => WarehouseApiController::class,
    'products' => ProductApiController::class,
    'purchases' => PurchaseApiController::class,
    'customers' => CustomerApiController::class,
    'orders' => OrderApiController::class,
    'stocks' => StockApiController::class,

]);
Route::get('/stocks/summary', [StockApiController::class, 'stockSummary']);

// Route::apiResource('deliveries' , OrderDeliveryApiController::class);


Route::get('/brands/{id}/find', [BrandApiController::class, 'find']);

// Order related POST actions
Route::post('/orders/{id}/payments', [OrderApiController::class, 'addPayment']);
Route::post('/orders/{id}/status', [OrderApiController::class, 'changeStatus']);
Route::get('/orders/{orderId}/items', [OrderApiController::class, 'getOrderItems']);


// Route::post('/orders/{id}/shipments', [OrderApiController::class, 'addShipment']);
// Route::post('{orderId}/delivery', [OrderApiController::class, 'addDelivery']);
// Route::get('{orderId}/delivery', [OrderApiController::class, 'getDelivery']);
// Route::post('/stock/delivery', [StockApiController::class, 'storeDelivery']);

Route::get('/deliveries', [OrderDeliveryApiController::class, 'index']);
Route::post('/orders/{orderId}/delivery', [OrderDeliveryApiController::class, 'store']);
Route::get('/orders/{orderId}/delivery', [OrderDeliveryApiController::class, 'show']);
Route::post('/stock/delivery', [StockApiController::class, 'storeDelivery']);
Route::patch('/deliveries/{id}/status', [OrderDeliveryApiController::class, 'updateStatus']);


