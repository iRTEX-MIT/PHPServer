<?php


class Router
{

    protected $path;

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
    public function __construct($path = "HTTP")
    {
        $this->path = $path;
    }


    public function getError404 ()
    {

        if ($this->isFile('/404.html')) {
            return $this->getHTML('/404.html');
        } else if (file_exists('.source/HTML/404.html')) {
            return $this->HTMLTemplate(file_get_contents('.source/HTML/404.html'));
        } else {
            return "Error: 404";
        }

    }

    public function getErrorNoIndex ()
    {

        if (file_exists('.source/HTML/no-index.html')) {
            return $this->HTMLTemplate(file_get_contents('.source/HTML/no-index.html'));
        } else {
            return "Error: index page not found";
        }

    }

    public function getFilesList ($file)
    {

        if (file_exists('.source/HTML/files.html')) {

            $files = [];

            foreach (glob("{$this->path}/{$file}/*") as $path) {
                $files[] = array(
                    'pathinfo' => pathinfo($path),
                    'revpath' => "{$file}/" . basename($path),
                    'isFile' => is_file($path)
                );
            }

            return $this->HTMLTemplate(file_get_contents('.source/HTML/files.html'), [
                'files' => $files,
                'file' => $file
            ]);

        } else {
            return "Error: index page not found";
        }

    }

    public function isFile ($file)
    {
        return file_exists("{$this->path}/{$file}") && is_file("{$this->path}/{$file}");
    }

    public function isDir ($file)
    {
        return file_exists("{$this->path}/{$file}") && is_dir("{$this->path}/{$file}");
    }

    public function contentType ($file)
    {
        if (file_exists("{$this->path}/{$file}")) {
            return mime_content_type("{$this->path}/{$file}");
        } else {
            return false;
        }
    }

    public function getContentOfFile ($file) {
        if ($this->isFile($file)) {
            return file_get_contents("{$this->path}/{$file}");
        } else {
            return false;
        }
    }

    public function getHTML ($file) {

        if ($this->isFile($file)) {
            return $this->HTMLTemplate($this->getContentOfFile($file));
        } else {
            return false;
        }
    }

    public function HTMLTemplate ($html, $options = []) {

        global $twig;
        global $cb_registry;
        global $cb_app;

        return $twig->createTemplate($html)->render([
            'cbr' => $cb_registry,
            'app' => $cb_app,
            'e' => $options
        ]);
    }

    public function route ($path) {

        global $cb_route;

        $cb_route_array = (array) $cb_route;

        if (isset($cb_route_array[$path])) {
            return $cb_route_array[$path];
        } else {
            return $path;
        }

    }

}