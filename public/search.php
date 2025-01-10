<?php
require_once __DIR__ . '/../src/Scraper.php';
require_once __DIR__ . '/../src/Filters.php';

$config = require __DIR__ . '/../config/config.php';
$scraper = new Scraper($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters = Filters::prepareFilters($_POST);
    $data = $scraper->fetchData($filters);
    echo $data;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Filtrar Datos</title>
</head>
<body>
    <h1>Busqueda Avanzada</h1>
    <form method="POST" action="">
        <label for="caratula">Carátula:</label>
        <input type="text" id="caratula" name="caratula"><br>

        <label for="expediente">Expediente:</label>
        <input type="text" id="expediente" name="expediente"><br>

        <label for="receptoria">Receptoría:</label>
        <input type="text" id="receptoria" name="receptoria"><br>

        <label for="fecha_desde">Fecha Desde:</label>
        <input type="date" id="fecha_desde" name="fecha_desde"><br>

        <label for="fecha_hasta">Fecha Hasta:</label>
        <input type="date" id="fecha_hasta" name="fecha_hasta"><br>

        <button type="submit">Buscar</button>
    </form>
</body>
</html>
