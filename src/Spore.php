<?php
namespace PHP_Spore
{
    use Doctrine\Common\Annotations\AnnotationRegistry;

    class Spore
    {
        protected $spec;
    /**
     * @param null $file
     * @return Spore
     * @throws Spore_Exception
     */
        public static function newFromJson($file = null)
        {
            if(!$file) {
                throw new Spore_Exception("File not specified");
            }

            $fp = file_get_contents($file);
            if (!$fp) {
                throw new Spore_Exception('Unable to open file: ' . $file);
            }

            $arr = json_decode($fp, true);
            if( json_last_error() != "JSON_ERROR_NONE" ) {
                throw new Spore_Exception('Cannot decode file: ' . $file);
            }

            return self::newFromArray($arr);
        }
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
            AnnotationRegistry::registerFile(__DIR__ . '/Spore_Property.php');
            $this->spec = $spec;
        }

    /**
     * Return loaded spec
     * @return array
     */
        public function spec()
        {
            return $this->spec;
        }

        protected function exec(array $call, array $arguments)
        {
            $method = strtolower($call["method"] ?? "GET");
            $url = $this->getRequestUrl($call, $arguments);
            $arguments = $this->getRequestParams($arguments);

            switch($method) {
                case "get":
                    $response = Spore_Request::get($url, $arguments)
                        ->send();
                    break;
                case "post":
                    $response = Spore_Request::post($url, $arguments)
                        ->send();
                    break;
                case "put":
                    $response = Spore_Request::put($url, $arguments)
                        ->send();
                    break;
                default:
                    throw new Spore_Exception('Invalid method: ' . $method, 1);
            }

            if(isset($call["model"])) {
                return (new Spore_Model($call["model"]))->hydrateFrom($response->json());
            }

            return $response->object();
        }
    /**
     * Returns the url for the request
     *
     * @param array $method
     * @return string
     */
        private function getRequestUrl(array $method, array $arguments = []) : string
        {
            $url = ( $method["override_url"] ?? $this->spec["base_url"] ). $method["path"];
            // Fill named params in url
            if(isset($method["named-params"])){ 
              foreach($method["named-params"] as $named) {
                if(!isset($arguments["named-data"][$named])) {
                  throw new Spore_Exception("Error Processing Request, cannot find named param: $named", 1);
                }
                $url = str_replace( ":$named", $arguments["named-data"][$named], $url);
              }
            }
            return $url;
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

    /**
     * Check data for required arguments
     * @param array $method
     * @param array $parameters
     * @return bool
     * @throws Spore_Exception
     */
        private function validateParameters(array $method, array $parameters = [])
        {
            if(isset($method["params"]) && !empty($method["params"])) {
                foreach($method["params"] as $param => $opts) {
                    if(is_integer($param) && is_string($opts)){
                        $param = $opts;  $opts = null;
                    }
                    // Check for required params
                    if($opts && isset($opts["required"])) {
                        if(!isset($parameters["params"][$param])) {
                            throw new Spore_Exception("Validation failed in requires parameter: " . $param);
                        }
                    }
                }
            }

            return true;
        }

        public function __call($name, $arguments = [])
        {
            if (!isset ($this->spec['methods'][$name])) {
                throw new Spore_Exception('Invalid method: "' . $name . '"');
            }

            $args =  $arguments[0] ?? [];
            $this->validateParameters($this->spec['methods'][$name], $args);
            return $this->exec($this->spec['methods'][$name], $args);
        }
    }
}
