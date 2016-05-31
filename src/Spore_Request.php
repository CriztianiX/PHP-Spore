<?php

namespace PHP_Spore {
    use \GuzzleHttp\Client as GuzzleHttpClient;

    class Spore_Request
    {
        protected $client;
        protected $method;
        protected $url;
        protected $args;
        protected $request;
    /**
     * Spore_Request constructor.
     * @param string $method
     * @param string $url
     * @param array $args
     * @return void
     */
        protected function __construct(string $method, string $url, array $args = [])
        {
            $this->client = new GuzzleHttpClient();
            $this->method = $method;
            $this->url = $url;
            $this->args = array_merge($args, [
                'exceptions' => false
            ]);

            $request = $this->createRequest();
            $this->setRequest($request);
        }

        protected function createRequest()
        {
            return $this->client->createRequest($this->method, $this->url, $this->args);
        }

        public function send()
        {
            $response = $this->client->send($this->request);
            $code = $response->getStatusCode();

            if ($code == 200) {
                return $response->getBody();
            }

            throw new \Exception("Error (".$code.") processing request: " . $this->url, 1);
        }
    /**
     * @param string $method
     * @param string $endpoint
     * @param array $args
     * @return Spore_Request
     */
        public static function request(string $method, string $endpoint, array $args = [])
        {
            return new Spore_Request($method, $endpoint, $args);
        }
    /**
     * Send get request
     *
     * @param string $url
     * @param array $args
     * @return Spore_Request
     */
        public static function get(string $url, array $args = [])
        {
            return self::request("GET", $url, $args);
        }
    /**
     * Send post request
     *
     * @param string $url
     * @param array $args
     * @return Spore_Request
     */
        public static function post(string $url, array $args = [])
        {
            return self::request("POST", $url, $args);
        }

    /**
     * Set request
     *
     * @param $request
     */
        public function setRequest($request)
        {
            $this->request = $request;
        }

        public function getRequest()
        {
            return $this->request;
        }
    }
}
