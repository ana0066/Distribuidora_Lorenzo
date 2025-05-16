<?php
// === CONFIGURACIÓN ===
$db_host = 'fdb1030.awardspace.net';
$db_user = '4630827_distribuidoral';
$db_pass = 'Lorenzo19*';
$db_name = '4630827_distribuidoral';
$backup_dir = __DIR__ . '/backups_total';
$fecha = date('Y-m-d_H-i-s');

// Crear carpeta si no existe
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

// === BACKUP DE BASE DE DATOS ===
$sql_file = "$backup_dir/backup_db_$fecha.sql";
$comando = "mysqldump --user=$db_user --password=$db_pass --host=$db_host $db_name > $sql_file";
system($comando, $resultado);

if ($resultado !== 0) {
    die("Error al hacer backup de la base de datos.");
}

// === BACKUP DE ARCHIVOS DEL SITIO ===
$zip_file = "$backup_dir/backup_files_$fecha.zip";
$zip = new ZipArchive();
if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
    die("No se pudo crear el archivo ZIP");
}

$folder = new RecursiveDirectoryIterator(__DIR__, RecursiveDirectoryIterator::SKIP_DOTS);
$files = new RecursiveIteratorIterator($folder);

foreach ($files as $file) {
    if (strpos($file, 'backups') !== false) continue; // Evita incluir los propios backups
    $filePath = $file->getRealPath();
    $relativePath = substr($filePath, strlen(__DIR__) + 1);
    $zip->addFile($filePath, $relativePath);
}

$zip->close();

echo "Backup completado. <a href='backups_total/'>Ver Backups</a>";
?>
