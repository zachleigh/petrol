<?php

namespace Petrol\Core\Config;

class Config
{
    private $config_file;

    /**
     * Construct.
     *
     * @param string $config_file [path to config file]
     */
    public function __construct($config_file = 'config.php')
    {
        $this->config_file = include $config_file;
    }

    /**
     * Get configuration value.
     *
     * @param string $key [config file key]
     *
     * @return string
     */
    public function get($key)
    {
        return $this->config_file[$key];
    }
}
