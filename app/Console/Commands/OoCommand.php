<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oo:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Projec init start');

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
            $this->info("Database '$databaseName' created successfully.");
        } catch (\Exception $e) {
            $this->error("Error creating database '$databaseName': " . $e->getMessage());
            return;
        }

        // İstediğiniz Artisan komutlarını buraya ekleyin
        $this->call('key:generate');
        $this->call('db:wipe');
        $this->call('migrate');
        // $this->call('permissions:sync');
        $this->call('db:seed');
        // $this->call('permissions:sync', ['--policies']);

        $this->info('Projec init end');
    }
}
