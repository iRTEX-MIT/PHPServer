<?php

use Nette\Neon\Neon;

class Config
{

    const REGISTRY_FILE = './conf/registry.yml';
    const VARIABLES = [
        '$~' => '~'
    ];

    private $system_props = [];
    private $configs = [];

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
    public function __construct()
    {

        $this->system_props = Neon::decode(file_get_contents(self::REGISTRY_FILE));

        array_walk_recursive($this->system_props, function(&$v, $k) {

            $merge = array_merge([
                '$root' => realpath(dirname(__FILE__) . '/..')
            ], self::VARIABLES);

            $v = str_replace(array_keys($merge), array_values($merge), $v);

        });

        $this->configs = [
            "config"    => Neon::decode(file_get_contents($this->system_props['path']['config'])),
            "app"       => Neon::decode(file_get_contents($this->system_props['path']['app'])),
            "route"     => Neon::decode(file_get_contents($this->system_props['path']['route'])),
            "registry"  => $this->system_props
        ];

    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param string $name
     * @return mixed
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __get($name)
    {
        return $this->configs[$name];
    }

    /**
     * run when writing data to inaccessible members.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
     */
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
    }


}