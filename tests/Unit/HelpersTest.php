<?php

use Petrol\tests\TestCase;
use Petrol\Core\Helpers\Traits\Helpers;

class HelpersTest extends TestCase
{
    use Helpers;

    /**
     * makeClassName()
     *
     * @test
     */
    public function make_class_name_makes_camel_case_name()
    {
        $result = $this->makeClassName('test_class_name');

        $this->assertEquals($result, 'TestClassName');
    }

    /**
     * makeClassName()
     *
     * @test
     */
    public function make_class_name_makes_camel_case_name_with_weird_underscores()
    {
        $result = $this->makeClassName('_test_class_name_');

        $this->assertEquals($result, 'TestClassName');
    }

    /**
     * underscoreToCamelCase()
     *
     * @test
     */
    public function underscore_to_camel_case_returns_variable_name()
    {
        $result = $this->underscoreToCamelCase('_test_class_name_');

        $this->assertEquals($result, 'testClassName');
    }
}
