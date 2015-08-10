<?php

namespace Petrol\Core\Commands;

trait CommandTrait
{
    /**
     * Find and return the Filler directory.
     *
     * @param string $path [user entered path]
     *
     * @return string
     */
    protected function findFillerDirectory($path)
    {
        if (isset($path)) {
            return $path;
        } elseif ($this->filesystem->exists(getcwd().'/vendor/zachleigh/petrol/src/Fillers/')) {
            return getcwd().'/vendor/zachleigh/petrol/src/Fillers/';
        } elseif (($this->filesystem->exists(getcwd().'/src/Fillers/'))) {
            return getcwd().'/src/Fillers/';
        }

        throw new \Exception('Filler directory can not be found.');
    }

    /**
     * Find and return path to Petrol.
     *
     * @return string
     */
    protected function findPetrolPath()
    {
        if ($this->filesystem->exists(getcwd().'/src')) {
            return getcwd();
        } elseif ($this->filesystem->exists(getcwd().'/vendor/zachleigh/petrol/')) {
            return getcwd().'/vendor/zachleigh/petrol/';
        }

        throw new \Exception('Petrol can not be found!');
    }
}
