<?php
namespace PHP_Spore
{
    class Spore
    {
        protected $spec;
    /**
     * @param array $spec
     * @return Spore
     */
        public static function newFromArray(array $spec) : Spore
        {
            return new Spore($spec);
        }
    /**
     * Spore constructor.
     * @param array $spec
     * @return void
     */
        protected function __construct(array $spec)
        {
            $this->spec = $spec;
        }

        protected function exec(array $call, array $arguments)
        {
            $method = strtolower($call["method"] ?? "GET");
            $url = $this->getRequestUrl($call);
            $arguments = $this->getRequestParams($arguments);

            switch($method) {
                case "get":
                    $request = Spore_Request::get($url)
                        ->send();
                    break;
                case "post":
                    $request = Spore_Request::post($url, $arguments)
                        ->send();
                    break;
                default:
                    throw new Spore_Exception('Invalid method: ' . $method, 1);
            }

            return $request;
        }
    /**
     * Returns the url for the request
     *
     * @param array $method
     * @return string
     */
        private function getRequestUrl(array $method) : string
        {
            return $this->spec["base_url"] . $method["path"];
        }
    /**
     * @param array $params
     * @return array
     */
        private function getRequestParams(array $arguments) : array
        {
            $params = [];
            if(isset($arguments["form-data"])) {
                $params["json"] = $arguments["form-data"];
            }
            if(isset($arguments["params"])) {
                $params["query"] = $arguments["params"];
            }

            return $params;
        }

        public function __call($name, $arguments)
        {
            if (!isset ($this->spec['methods'][$name])) {
                throw new Spore_Exception('Invalid method: "' . $name . '"');
            }

            return $this->exec($this->spec['methods'][$name], $arguments[0]);
        }
    }
}
