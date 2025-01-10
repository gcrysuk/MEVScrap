<?php
require_once __DIR__ . '/../src/Scraper.php';
require_once __DIR__ . '/../src/Filters.php';

$config = require __DIR__ . '/../config/config.php';
$scraper = new Scraper($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters = Filters::prepareFilters($_POST);
    $data = $scraper->fetchData($filters);

    // Consolidar resultados
    foreach ($data as $result) {
        echo $result;
    }
    exit;
}
?>
