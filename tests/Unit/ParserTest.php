<?php

use Petrol\Core\Helpers\Traits\Parser;
use Petrol\tests\TestCase;

class ParserTest extends TestCase
{
    use Parser;

    /**
     * cleanExplode()
     *
     * @test
     */
    public function clean_explode_with_slash()
    {
        $string = 'this / is / a / test';

        $expected = [
            'this',
            'is',
            'a',
            'test'
        ];

        $result = $this->cleanExplode('/', $string);

        $this->assertEquals($result, $expected);
    }

    /**
     * cleanExplode()
     *
     * @test
     */
    public function clean_explode_with_space()
    {
        $string = 'this is a test';

        $expected = [
            'this',
            'is',
            'a',
            'test'
        ];

        $result = $this->cleanExplode(' ', $string);

        $this->assertEquals($result, $expected);
    }

    /**
     * cleanExplode()
     *
     * @test
     */
    public function clean_explode_with_new_line()
    {
        $string = 'this is a test' . "\n" . ' continued';

        $expected = [
            'this',
            'is',
            'a',
            'test',
            'continued'
        ];

        $result = $this->cleanExplode(' ', $string);

        $this->assertEquals($result, $expected);
    }
}
