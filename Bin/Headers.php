<?php


class Headers
{

    private $code = 200;
    private $status = "OK";
    private $len = 0;
    private $content_type = "text/html";

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct($code = 200, $status = "OK", $len = 0, $content_type = "text/html")
    {
        $this->code = $code;
        $this->status = $status;
        $this->len = $len;
        $this->content_type = $content_type;
    }


    public function getHeaders () {
        return "HTTP/1.1 {$this->code} {$this->status} \r\n" .
        "Date: Fri, 31 Dec 1999 23:59:59 GMT \r\n" .
        "Content-Length: {$this->len} \r\n" .
        "Content-Type: {$this->content_type} \r\n\r\n";
    }

    /**
     * @return int|mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed|string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int|mixed
     */
    public function getLen()
    {
        return $this->len;
    }

    /**
     * @return mixed|string
     */
    public function getContentType()
    {
        return $this->content_type;
    }


}