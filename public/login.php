#### 3. `public/login.php`
```php
<?php
require_once __DIR__ . '/../src/Scraper.php';

$config = require __DIR__ . '/../config/config.php';
$scraper = new Scraper($config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $loginResponse = $scraper->login($username, $password);

    if (strpos($loginResponse, 'Bienvenido') !== false) {
        echo "Login exitoso";
    } else {
        echo "Error en el login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
```

#### 4. `public/index.php`
```php
<?php
require_once __DIR__ . '/../src/Scraper.php';

$config = require __DIR__ . '/../config/config.php';
$scraper = new Scraper($config);

$data = $scraper->fetchData($config['data_url']);

echo $data;
?>
```