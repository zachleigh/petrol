<?php

namespace Petrol\Core\Helpers\Traits;

trait Helpers
{
    /**
     * Make a camel-case class name.
     *
     * @param string  $string
     *
     * @return string
     */
    function makeClassName($string)
    {
        $string = $this->removeSpaces($string);

        $string = $this->underscoreToCamelCase($string);

        return ucfirst($string);
    }

    /**
     * Removes spaces from string.
     *
     * @param  string  $string
     *
     * @return string
     */
    function removeSpaces($string)
    {
        return str_replace(' ', '_', $string);
    }

    /**
     * Make an underscored word camel-case.
     *
     * @param   string  $string
     *
     * @return  string
     */
    function underscoreToCamelCase($string)
    {
        $string = strtolower($string);

        $string = trim($string, '_');

        while (strpos($string, '_')) {
            $index = strpos($string, '_');

            $letter = strtoupper($string[$index + 1]);

            $string[$index + 1] = $letter;

            $string = substr_replace($string, '', $index, 1);
        }

        $string = str_replace('_', '', $string);

        return $string;
    }
}
