<?php

use Petrol\tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * makeClassName()
     *
     * @test
     */
    public function make_class_name_makes_camel_case_name()
    {
        $result = makeClassName('test_class_name');

        $this->assertEquals($result, 'TestClassName');
    }

    /**
     * makeClassName()
     *
     * @test
     */
    public function make_class_name_makes_camel_case_name_with_weird_underscores()
    {
        $result = makeClassName('_test_class_name_');

        $this->assertEquals($result, 'TestClassName');
    }

    /**
     * underscoreToCamelCase()
     *
     * @test
     */
    public function underscore_to_camel_case_returns_variable_name()
    {
        $result = underscoreToCamelCase('_test_class_name_');

        $this->assertEquals($result, 'testClassName');
    }
}
