<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class CopyFromMysqlViaPdoSeeder extends Seeder
{
    public function run(): void
    {
        $host = env('DB_OLD_HOST', '127.0.0.1');
        $port = env('DB_OLD_PORT', '3306');
        $db   = env('DB_OLD_DATABASE');
        $user = env('DB_OLD_USERNAME');
        $pass = env('DB_OLD_PASSWORD');

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('No se pudo conectar a MySQL (PDO): '.$e->getMessage());
        }

        // Orden por FK si aplica. Ajusta nombres si tus tablas difieren.
        $this->copy($pdo, 'categories');
        $this->copy($pdo, 'users');
        $this->copy($pdo, 'questions');
        $this->copy($pdo, 'answers');
        $this->copy($pdo, 'comments');
        $this->copy($pdo, 'hearts');
    }

    protected function copy(PDO $pdo, string $table): void
    {
        // Lee TODO de MySQL
        $stmt = $pdo->query("SELECT * FROM `{$table}`");
        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
            // Limpia destino para evitar duplicados
            DB::table($table)->delete();

            // Inserta en lotes
            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table($table)->insert($chunk);
            }
        }
    }
}