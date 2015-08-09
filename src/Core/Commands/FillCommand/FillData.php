<?php

namespace Petrol\Core\Commands\FillCommand;

class FillData
{
    /**
     * The path to the database filler file.
     *
     * @var string
     */
    public $file_path;

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
     * @param string $file_path
     * @param bool   $errors
     * @param string $config_path
     * @param string $source
     */
    public function __construct($file_path, $errors, $config_path, $source)
    {
        $this->file_path = $file_path;
        $this->errors = $errors;
        $this->config_path = $config_path;
        $this->source = $source;
    }
}
