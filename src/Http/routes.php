<?php

use Dcat\Admin\XqwLog\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('xqw-log', Controllers\XqwLogController::class.'@index');


Route::get('auth/xqw-logs', Controllers\XqwLogController::class.'@index')->name('dcat-admin.xqw-log.index');
Route::delete('auth/xqw-logs/{id}', Controllers\XqwLogController::class.'@destroy')->name('dcat-admin.xqw-log.destroy');
