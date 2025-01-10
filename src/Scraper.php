<?php
class Scraper
{
    private $config;
    private $cookieFile;

    public function __construct($config)
    {
        $this->config = $config;
        $this->cookieFile = __DIR__ . '/../storage/cookies.txt';
    }

   public function login($username, $password)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->config['login_url']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'usuario' => $username,
        'clave' => $password
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

    $response = curl_exec($ch);

    // Si hay redirección explícita a POSLoguin.asp
    if (strpos($response, 'POSLoguin.asp') !== false) {
        curl_setopt($ch, CURLOPT_URL, 'https://mev.scba.gov.ar/POSLoguin.asp');
        $response = curl_exec($ch);
    }

    curl_close($ch);

    file_put_contents(__DIR__ . '/../storage/login_response.html', $response);

    return $response;
}

    public function fetchData($filters)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['filter_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($filters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        file_put_contents(__DIR__ . '/../storage/login_response.html', $response);

    return $response;
    }
}
