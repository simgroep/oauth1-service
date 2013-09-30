<?php

namespace Simgroep\Oauth1Service;

class Zf1Request extends Request
{
    /**
     * @var \Zend_Controller_Request_Http
     */
    protected $request;

    /**
     * @var Header
     */
    public $header;

    public function __construct(\Zend_Controller_Request_Http $request)
    {
        $this->request = $request;
        $this->header = new Header($request->getHeader('authorization'));
    }

    public function getRequestMethod()
    {
        return $this->request->getMethod();
    }

    public function getRequestUri()
    {
        return $this->request->getScheme() . '://' . $this->request->getHttpHost() . rtrim($this->request->getRequestUri(), '?');
    }

    public function getRequestParameters()
    {
        if ($this->request->isPost()) {
            return $this->request->getPost();
        } else {
            return $this->request->getQuery();
        }
    }
}

