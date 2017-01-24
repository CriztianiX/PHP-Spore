<?php

namespace PHP_Spore {
    use GuzzleHttp\Client;
    use PHP_Spore\Spore_Response;

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
            $this->client = new Client();
            $this->method = $method;
            $this->url = $url;
            $this->args = array_merge($args, [
                'exceptions' => false
            ]);
        }

        public function send()
        {
            $response = $this->client->request($this->method, $this->url, $this->args);
            $code = $response->getStatusCode();
            if ($code != 200) {
                throw new \Exception("Error (".$code.") processing request: " . $this->url, 1);
            }

            return new Spore_Response($response);
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
     * Send put request
     *
     * @param string $url
     * @param array $args
     * @return Spore_Request
     */
        public static function put(string $url, array $args = [])
        {
            return self::request("PUT", $url, $args);
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
    }
}
