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
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function getDepartmentsAndOrganisms()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->config['filter_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

        $response = curl_exec($ch);
        curl_close($ch);

        // Parse the response to extract department and organism options
        $departments = []; // Extract department values
        $organisms = [];   // Extract organism values

        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);

        // Example XPath queries to extract dropdown options
        $departmentOptions = $xpath->query('//select[@name="DtoJudElegido"]/option');
        foreach ($departmentOptions as $option) {
            $departments[$option->getAttribute('value')] = $option->nodeValue;
        }

        // Modify this query for organism options as necessary
        $organismOptions = $xpath->query('//select[@name="organism_select"]/option');
        foreach ($organismOptions as $option) {
            $organisms[$option->getAttribute('value')] = $option->nodeValue;
        }

        return ['departments' => $departments, 'organisms' => $organisms];
    }

    public function fetchData($filters)
    {
        $results = [];
        $options = $this->getDepartmentsAndOrganisms();

        foreach ($options['departments'] as $departmentId => $departmentName) {
            foreach ($options['organisms'] as $organismId => $organismName) {
                $filters['DtoJudElegido'] = $departmentId;
                $filters['organism'] = $organismId;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->config['filter_url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($filters));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
                curl_setopt($ch, CURLOPT_USERAGENT, $this->config['user_agent']);

                $response = curl_exec($ch);
                curl_close($ch);

                // Aggregate results
                $results[] = $response;
            }
        }

        return $results;
    }
}
