<?php

namespace Petrol\Core\Commands\FillCommand;

class FillData
{
    /**
     * Namespace of the database filler file.
     *
     * @var string
     */
    public $file_namespace;

    /**
     * If true, database PDO errors will be shown.
     *
     * @var bool
     */
    public $errors;

    /**
     * Full path to config file to use.
     *
     * @var string
     */
    public $config_path;

    /**
     * Source of data.
     *
     * @var string [console, artisan, test]
     */
    public $source;

    /**
     * Construct.
     *
     * @param string $file_namespace
     * @param bool   $errors
     * @param string $config_path
     * @param string $source
     */
    public function __construct($file_namespace, $errors, $config_path, $source)
    {
        $this->file_namespace = $file_namespace;
        $this->errors = $errors;
        $this->config_path = $config_path;
        $this->source = $source;
    }
}
