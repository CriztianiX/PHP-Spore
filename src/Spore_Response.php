<?php
namespace PHP_Spore;

class Spore_Response
{
    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getBody()
    {
        return $this->response->getBody();
    }

    public function object()
    {
        return json_decode($this->response->getBody());
    }

    public function json()
    {
      $res = json_decode($this->response->getBody(), true);

      return $res;
    }

    private $response;
}