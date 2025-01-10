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
        curl_close($ch);

        return $response;
    }

    public function getOptions()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['filter_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

        $response = curl_exec($ch);
        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);

        $departments = [];
        $departmentNodes = $xpath->query('//select[@name="DtoJudElegido"]/option');
        foreach ($departmentNodes as $node) {
            $departments[$node->getAttribute('value')] = $node->nodeValue;
        }

        return $departments;
    }

    public function fetchData($filters)
    {
        $departments = $this->getOptions();
        $results = [];

        foreach ($departments as $departmentId => $departmentName) {
            $filters['DtoJudElegido'] = $departmentId;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->config['filter_url']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($filters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

            $response = curl_exec($ch);
            curl_close($ch);

            $results[] = [
                'department' => $departmentName,
                'response' => $response
            ];
        }

        return $results;
    }
}
