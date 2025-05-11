<?php
$host = "fdb1030.awardspace.net";
$user = "4630827_distribuidoral";
$pass = "Lorenzo19*";
$dbname = "4630827_distribuidoral";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$backupFile = "backups/{$dbname}_" . date("Y-m-d_H-i-s") . ".sql";
$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {
    // Estructura
    $result = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $result->fetch_row();
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";

    // Datos
    $result = $conn->query("SELECT * FROM `$table`");
    $columnCount = $result->field_count;

    while ($row = $result->fetch_row()) {
        $sqlScript .= "INSERT INTO `$table` VALUES(";
        for ($i = 0; $i < $columnCount; $i++) {
            if (isset($row[$i])) {
                $value = $conn->real_escape_string($row[$i]);
                $value = str_replace("\n", "\\n", $value);
                $sqlScript .= "'$value'";
            } else {
                $sqlScript .= "NULL";
            }
            if ($i < ($columnCount - 1)) $sqlScript .= ',';
        }
        $sqlScript .= ");\n";
    }
}

if (!file_exists('backups')) {
    mkdir('backups', 0777, true);
}
file_put_contents($backupFile, $sqlScript);

echo "Backup realizado: <a href='$backupFile'>Descargar aquí</a>";

$conn->close();
?>
