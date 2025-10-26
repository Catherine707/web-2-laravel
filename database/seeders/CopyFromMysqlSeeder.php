<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CopyFromMysqlSeeder extends Seeder
{
    public function run(): void
    {
        // Define la conexión mysql_old en runtime (toma valores de .env DB_OLD_*)
        config(['database.connections.mysql_old' => [
            'driver' => 'mysql',
            'host' => env('DB_OLD_HOST', '127.0.0.1'),
            'port' => env('DB_OLD_PORT', '3306'),
            'database' => env('DB_OLD_DATABASE', 'forge'),
            'username' => env('DB_OLD_USERNAME', 'forge'),
            'password' => env('DB_OLD_PASSWORD', ''),
            'unix_socket' => env('DB_OLD_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]]);

        // (Opcional) Valida conexión
        DB::connection('mysql_old')->getPdo();

        // Copia tablas (ajusta nombres si difieren)
        $this->copy('categories');
        $this->copy('users');
        $this->copy('questions');
        $this->copy('answers');
        $this->copy('comments');
        $this->copy('hearts');
    }

    protected function copy(string $table): void
    {
        $rows = DB::connection('mysql_old')->table($table)->get()
            ->map(fn ($r) => (array) $r)->all();

        if (! empty($rows)) {
            DB::table($table)->delete();

            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table($table)->insert($chunk);
            }
        }
    }
}