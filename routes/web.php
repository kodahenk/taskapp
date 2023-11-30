<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset', function () {
    if (!File::exists(base_path('.env'))) {
        // .env.example dosyasını kopyala
        File::copy(base_path('.env.example'), base_path('.env'));

        // Kullanıcıdan gerekli bilgileri al
        $this->info('Please provide the necessary information in the .env file.');
        $this->info('You can edit the .env file directly or run "php artisan key:generate" to generate an application key.');
    }

    $databaseName = config('database.connections.mysql.database');

    try {
        // Eğer veritabanı yoksa oluştur
        DB::connection('mysql_only_connect')->getPdo()->exec("CREATE DATABASE IF NOT EXISTS $databaseName");
    } catch (\Exception $e) {
        return;
    }

    Artisan::call('key:generate');
    Artisan::call('db:wipe');
    Artisan::call('migrate');
    Artisan::call('db:seed');

    return "oki";
});
