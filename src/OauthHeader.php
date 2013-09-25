<?php

class OauthHeader implements ArrayAccess
{
    protected $header = '';

    protected $headerParts = array();

    public function __construct($header)
    {
        $this->header = $header;
        $this->explodeIntoParts();
    }


    protected function explodeIntoParts()
    {
        if (substr($this->header,0,5) !== 'OAuth') {
            throw new OauthException('Header is not correct.');
        }
        $header = str_replace('OAuth ', '', $this->header);
        $parts = explode(',', $header);
        array_walk($parts, function(&$value) {
            $value = trim($value);
        });
        foreach($parts as $part) {
            list($key, $value) = explode('=', $part);
            $key = str_replace('oauth_', '', $key);
            $this->headerParts[$key] = urldecode(trim($value, '"'));
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->headerParts);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->headerParts[$offset];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new OAuthException('Setting values is not allowed.');
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new OAuthException('Unsetting values is not allowed.');
    }
}
