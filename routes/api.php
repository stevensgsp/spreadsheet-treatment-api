<?php

use App\Http\Controllers\TenderController;
use App\Http\Controllers\UploadedFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('tenders')->group(function () {
    // import tenders
    Route::post('/import', [TenderController::class, 'import']);

    // index of tenders
    Route::get('/', [TenderController::class, 'index']);

    // show a tender
    Route::get('/{tenderId}', [TenderController::class, 'show']);

    // check if tender was read
    Route::get('/{tenderId}/was-read', [TenderController::class, 'wasRead']);
});

Route::prefix('uploaded-files')->group(function () {
    // index of uploaded files
    Route::get('/', [UploadedFileController::class, 'index']);

    // show an uploaded file
    Route::get('/{uploadedFileId}', [UploadedFileController::class, 'show']);
});
