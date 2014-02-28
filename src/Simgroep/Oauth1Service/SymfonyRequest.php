<?php

namespace Simgroep\Oauth1Service;

use Symfony\Component\HttpFoundation\Request as SymfonyHttpRequest;

class SymfonyRequest extends Request
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var Header
     */
    public $header;

    public function __construct(SymfonyHttpRequest $request)
    {
        $this->request = $request;
        $this->header = new Header($request->headers->get('authorization'));
    }

    public function getRequestMethod()
    {
        return $this->request->getMethod();
    }

    public function getRequestUri()
    {
        return $this->request->getSchemeAndHttpHost() . rtrim($this->request->getRequestUri(), '?');
    }

    public function getRequestParameters()
    {
        if ($this->request->getRealMethod() === 'POST') {
            return $this->request->request->all();
        } else {
            return $this->request->query->all();
        }
    }
}

