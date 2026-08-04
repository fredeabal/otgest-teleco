<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportLegacySqlite extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:import-legacy-sqlite';
    protected $description = 'Importa datos operativos (ordenes, imagenes, plantillas) desde otro archivo de base de datos SQLite (.sqlite o .db).';
    protected $usage       = 'db:import-legacy-sqlite [ruta_al_archivo_sqlite]';
    protected $arguments   = [
        'ruta_al_archivo_sqlite' => 'Ruta absoluta o relativa al archivo de base de datos SQLite de origen.'
    ];

    public function run(array $params)
    {
        $filePath = $params[0] ?? null;

        if (empty($filePath)) {
            CLI::error("Por favor, especifica la ruta del archivo SQLite de origen.");
            return;
        }

        if (!file_exists($filePath)) {
            CLI::error("El archivo no existe en la ruta: {$filePath}");
            return;
        }

        CLI::write("Conectando a la base de datos de origen: {$filePath}...", "yellow");

        try {
            $sourceDb = new \SQLite3($filePath);
        } catch (\Exception $e) {
            CLI::error("No se pudo abrir el archivo SQLite de origen: " . $e->getMessage());
            return;
        }

        // Validar tablas de origen
        $tables = ['ordenes', 'plantillas', 'imagenes'];
        foreach ($tables as $table) {
            $check = $sourceDb->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='{$table}'");
            if (!$check) {
                CLI::error("La base de datos de origen no es compatible (falta la tabla '{$table}').");
                $sourceDb->close();
                return;
            }
        }

        $destDb = \Config\Database::connect();
        
        // Confirmación
        $confirm = CLI::prompt("Esta acción borrará los datos actuales de 'ordenes', 'plantillas' e 'imagenes' en la base de datos destino y los reemplazará con los de origen. ¿Deseas continuar? (y/n)", 'n', 'required');
        if (strtolower($confirm) !== 'y') {
            CLI::write("Operación cancelada.", "yellow");
            $sourceDb->close();
            return;
        }

        $destDb->query("PRAGMA foreign_keys = OFF;");
        $destDb->transBegin();

        try {
            // 1. Limpiar tablas actuales
            CLI::write("Limpiando tablas de destino...", "yellow");
            $destDb->table('imagenes')->truncate();
            $destDb->table('ordenes')->truncate();
            $destDb->table('plantillas')->truncate();

            // 2. Importar Plantillas
            CLI::write("Importando plantillas...", "yellow");
            $result = $sourceDb->query("SELECT * FROM plantillas");
            $countPlantillas = 0;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $destDb->table('plantillas')->insert($row);
                $countPlantillas++;
            }

            // 3. Importar Ordenes
            CLI::write("Importando órdenes de trabajo...", "yellow");
            $result = $sourceDb->query("SELECT * FROM ordenes");
            $countOrdenes = 0;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $destDb->table('ordenes')->insert($row);
                $countOrdenes++;
            }

            // 4. Importar Imagenes
            CLI::write("Importando imágenes...", "yellow");
            $result = $sourceDb->query("SELECT * FROM imagenes");
            $countImagenes = 0;
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $destDb->table('imagenes')->insert($row);
                $countImagenes++;
            }

            if ($destDb->transStatus() === false) {
                $destDb->transRollback();
                CLI::error("Error en la transacción. Se realizó un rollback.");
            } else {
                $destDb->transCommit();
                CLI::write("¡Importación completada con éxito!", "green");
                CLI::write(" - Plantillas importadas: {$countPlantillas}", "green");
                CLI::write(" - Órdenes importadas: {$countOrdenes}", "green");
                CLI::write(" - Imágenes importadas: {$countImagenes}", "green");
            }

        } catch (\Exception $e) {
            $destDb->transRollback();
            CLI::error("Ocurrió un error durante la importación: " . $e->getMessage());
        }

        $destDb->query("PRAGMA foreign_keys = ON;");
        $sourceDb->close();
    }
}
