<?php

namespace Petrol\Core\Contracts;

use Petrol\Core\Database\Connection;

abstract class Filler
{
    private $file_handle;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->connection->setStatement($this->columns, $this->table);
    }

    /**
     * Close file handle if it exists.
     */
    public function __destruct()
    {
        if ($this->file_handle) {
            $this->closeFile();
        }
    }

    /**
     * Parse the file.
     *
     * @param string $line [single line from file]
     *
     * @return array [Database column names as keys]
     */
    abstract protected function parse($line);

    /**
     * Parse the file and fill the database.
     */
    public function execute()
    {
        $this->file_handle = $this->loadFile();

        $this->loadVariables();

        $this->loadStatements();

        while (($line = fgets($this->file_handle)) !== false) {
            $data = $this->parse($line);

            if (is_array($data) && $this->fill === 'auto') {
                $this->connection->insertRow($data);
            } elseif ($this->fill === 'dump') {
                print_r($data);
            }
        }

        $this->closeFile();
    }

    /**
     * Validate the filler.
     */
    public function validate()
    {
        $this->validateColumns();

        $this->validateFile();

        $this->validateFill();

        $this->validateTable();

        $this->validateVariables();
    }

    /**
     * Close the file.
     *
     * @param  Resource $handle
     */
    protected function closeFile()
    {
        fclose($this->file_handle);

        $this->file_handle = null;
    }

    /**
     * Get the full path of the file.
     *
     * @return string
     */
    protected function getFullFilePath()
    {
        if (file_exists(getcwd().'/src/Files/'.$this->file)) {
            return getcwd().'/src/Files/'.$this->file;
        } elseif (file_exists(getcwd().'/app/Petrol/Files/'.$this->file)) {
            return getcwd().'/app/Petrol/Files/'.$this->file;
        }

        return false;
    }

    /**
     * Load the file to be parsed.
     *
     * @return Resource [file handle]
     */
    protected function loadFile()
    {
        $file = $this->getFullFilePath();

        $this->validateFileExists($file);

        $handle = fopen($file, 'r') or die('<error>Failed to open file</error>');

        return $handle;
    }

    /**
     * Set statements in $statements to object properties.
     */
    private function loadStatements()
    {
        foreach ($this->statements as $key => $statement) {
            $this->$key = $this->connection->prepareStatement($statement);
        }
    }

    /**
     * Set variables in $variables to object properties.
     */
    private function loadVariables()
    {
        foreach ($this->variables as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Throw exception if columns property is empty.
     */
    private function validateColumns()
    {
        if (empty($this->columns)) {
            throw new \Exception('Invalid filler file.'."\n".'$columns property not set.');
        }
    }

    /**
     * Throw exception if file property is not set.
     */
    private function validateFile()
    {
        if (is_null($this->file)) {
            throw new \Exception('Invalid filler file.'."\n".'$file property not set.');
        }
    }

    /**
     * Throw exception if file does not exists
     * 
     * @param  string $file 
     */
    private function validateFileExists($file)
    {
        if (!is_file($file)) {
            throw new \Exception('File does not exist.'."\n".'Please be sure $file is correctly set.');
        }
    }

    /**
     * Throw exception if fill property incorrectly set.
     */
    private function validateFill()
    {
        $accepted = ['auto', 'manual', 'dump'];

        if (!in_array($this->fill, $accepted)) {
            throw new \Exception('Invalid filler file.'."\n".'$fill property set to unknown value.');
        }
    }

    /**
     * Throw exception if table property is empty.
     */
    private function validateTable()
    {
        if (is_null($this->table) || empty($this->table)) {
            throw new \Exception('Invalid filler file.'."\n".'$table property not set.');
        }
    }

    /**
     * Throw exception if variables is not array.
     */
    private function validateVariables()
    {
        if (!is_array($this->variables)) {
            throw new \Exception('Invalid filler file.'."\n".'$variables property must be array.');
        }
    }
}
