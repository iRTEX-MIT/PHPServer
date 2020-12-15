<?php

use sekjun9878\RequestParser\RequestParser;
use sekjun9878\RequestParser\Request;
use Codedungeon\PHPCliColors\Color;


class Server
{

    protected $ip = null;
    protected $port = null;

    private $socket = null;
    private $client = null;
    private $router = null;

    const SOCKET_DOMAIN = AF_INET;
    const SOCKET_TYPE = SOCK_STREAM;
    const PATH = "./HTTP";

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
     *
     * @param string $ip
     * @param int $port
     */
    public function __construct($ip = 'localhost', $port = 80)
    {

        $this->ip = $ip;
        $this->port = $port;

        $this->socket = socket_create(self::SOCKET_DOMAIN, self::SOCKET_TYPE, 0);
        socket_bind($this->socket, $this->ip, $this->port) or new Exception("The address is already in use - {$this->ip}:{$this->port}");

        $this->router = new Router();

    }

    public function listen () {

        while(1)
        {

            socket_listen($this->socket);

            $this->client = socket_accept($this->socket);

            $input = socket_read($this->client, 1024);

            $requestParser = new RequestParser();
            $requestParser->addData($input);

            $request = Request::create($requestParser->exportRequestState());

            if ($request->getMethod()) {

                if ($request->getPath() == '/') {

                    $route = '/index.html';
                    $filetype = $this->router->contentType($route);

                    if ($this->router->isFile($route)) {
                        $content = $this->router->getContentOfFile($route);
                    } else {
                        $content = $this->router->getErrorNoIndex();
                    }

                    $headers = new Headers(200, "OK", strlen($content), $filetype);

                } else {

                    $route = $this->router->route($request->getPath());
                    $filetype = $this->router->contentType($route);

                    if ($this->router->isFile($route)) {

                        if ($filetype == 'text/html') {
                            $content = $this->router->getHTML($route);
                        } else {
                            $content = $this->router->getContentOfFile($route);
                        }

                        $headers = new Headers(200, "OK", strlen($content), $filetype);

                    } elseif ($this->router->isDir($route)) {

                        $content = $this->router->getFilesList($route);
                        $headers = new Headers(200, "OK", strlen($content), $filetype);

                    } else {

                        $content = $this->router->getError404();
                        $headers = new Headers(404, "Not Found", strlen($content), $filetype);

                    }

                }

                $document = "{$headers->get()} {$content}";

                echo Color::WHITE, Color::BOLD, "[{$request->getMethod()} {$this->router->contentType($route)}] {$request->getPath()}", Color::RESET, PHP_EOL;

                socket_write($this->client, $document, strlen($document));
                socket_close($this->client);

            }

        }

    }

}