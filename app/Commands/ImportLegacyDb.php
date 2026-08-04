<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportLegacyDb extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-legacy';
    protected $description = 'Importa datos operativos (ordenes, imagenes, plantillas) de un volcado MySQL (.sql) a la DB SQLite actual.';
    protected $usage       = 'db:import-legacy [ruta_al_archivo_sql]';
    protected $arguments   = [
        'ruta_al_archivo_sql' => 'Ruta absoluta o relativa al archivo SQL de volcado de MySQL.'
    ];

    // ---------------------------------------------------------------------
    // Ejecución principal del comando
    // ---------------------------------------------------------------------
    public function run(array $params)
    {
        $filePath = $params[0] ?? null;

        if (empty($filePath)) {
            CLI::error("Por favor, especifica la ruta del archivo SQL.");
            return;
        }

        if (!file_exists($filePath)) {
            CLI::error("El archivo no existe en la ruta: {$filePath}");
            return;
        }

        CLI::write("Leyendo y procesando el archivo SQL: {$filePath}...", "yellow");

        $db = \Config\Database::connect();
        
        // Desactivar temporalmente claves foráneas en SQLite
        $db->query("PRAGMA foreign_keys = OFF;");

        $handle = fopen($filePath, "r");
        if (!$handle) {
            CLI::error("No se pudo abrir el archivo SQL.");
            return;
        }

        $currentQuery = "";
        $inTargetInsert = false;
        $targetTables = ['ordenes', 'imagenes', 'plantillas'];
        $importedCount = 0;

        $db->transBegin();

        try {
            while (($line = fgets($handle)) !== false) {
                // Limpiar espacios en los extremos
                $trimmedLine = trim($line);

                // Detectar el inicio de un INSERT de nuestras tablas objetivo
                if (preg_match('/^INSERT INTO `(' . implode('|', $targetTables) . ')`/i', $trimmedLine, $matches)) {
                    $inTargetInsert = true;
                    $currentQuery = $line;
                } elseif ($inTargetInsert) {
                    $currentQuery .= $line;
                }

                // Si estamos en un insert y encontramos el final de la sentencia (;)
                if ($inTargetInsert && substr($trimmedLine, -1) === ';') {
                    // Procesar y limpiar la consulta para compatibilidad con SQLite
                    $cleanQuery = $this->cleanForSqlite($currentQuery);

                    // Ejecutar la consulta en SQLite
                    $db->query($cleanQuery);
                    $importedCount++;

                    // Resetear el buffer
                    $currentQuery = "";
                    $inTargetInsert = false;
                }
            }

            fclose($handle);

            if ($db->transStatus() === false) {
                $db->transRollback();
                CLI::error("Error en la transacción. Se realizó un rollback.");
            } else {
                $db->transCommit();
                CLI::write("¡Importación exitosa! Se procesaron {$importedCount} bloques de inserción.", "green");
            }

        } catch (\Exception $e) {
            fclose($handle);
            $db->transRollback();
            CLI::error("Ocurrió un error durante la importación: " . $e->getMessage());
        }

        // Reactivar claves foráneas
        $db->query("PRAGMA foreign_keys = ON;");
    }

    // ---------------------------------------------------------------------
    // Limpia y convierte sentencias INSERT de MySQL a SQLite
    // ---------------------------------------------------------------------
    private function cleanForSqlite(string $sql): string
    {
        // 1. Reemplazar los escapes de comillas simples de MySQL (\') por estándar SQL ('')
        // En MySQL las comillas se escapan como \', en SQLite se deben doblar ''
        // Usamos una expresión regular para evitar doblar barras invertidas consecutivas erróneamente.
        $sql = preg_replace("/(?<!\\\\)\\\\'/", "''", $sql);

        // 2. Reemplazar otros escapes comunes de MySQL
        $sql = str_replace(['\\"', '\\\\', '\\r', '\\n'], ['"', '\\', "\r", "\n"], $sql);

        return $sql;
    }
}
