<?php

namespace Petrol\Core\Helpers\Traits;

trait Parser
{
    /**
     * Explode string and trim each array item.
     *
     * @param string $character
     * @param string $string
     *
     * @return array
     */
    public function cleanExplode($character, $string)
    {
        $values = explode($character, $string);

        return array_map('trim', $values);
    }
}
