<?php

use Petrol\Core\Helpers\Traits\XmlParser;
use Petrol\tests\TestCase;

class XmlParserTest extends TestCase
{
    use XmlParser;

    public $xml_mock = [
        '<book id="bk101">',
        '<author>Gambardella, Matthew</author>',
        '<title>XML Developer\'s Guide</title>',
        '<genre>Computer</genre>',
        '<price>44.95</price>',
        '<publish_date>2000-10-01</publish_date>',
        '<description>An in-depth look at creating applications with XML.</description>',
        '</book>'
    ];

    public $xml_mock_expected = [
        '@attributes' => [
            'id' => 'bk101'
        ],
        'author' => 'Gambardella, Matthew',
        'title' => 'XML Developer\'s Guide',
        'genre' => 'Computer',
        'price' => '44.95',
        'publish_date' => '2000-10-01',
        'description' => 'An in-depth look at creating applications with XML.'
    ];

    /**
     * xmlToArray()
     *
     * @test
     */
    public function xml_to_array_returns_array()
    {
        foreach ($this->xml_mock as $line) {
            $result = $this->xmlToArrays($line, 'book');
        }
        
        $this->assertEquals($result, $this->xml_mock_expected);
    }

    /**
     * setTags()
     *
     * @test
     */
    public function set_tags_sets_tags()
    {
        $this->setTags('test');

        $this->assertEquals($this->open_tag, '<test');

        $this->assertEquals($this->close_tag, '</test>');
    }

    /**
     * isElement()
     *
     * @test
     */
    public function is_element_with_both_tags()
    {
        $result = $this->isElement('<element>Test</element>');

        $this->assertTrue($result);
    }

    /**
     * isElement()
     *
     * @test
     */
    public function is_element_with_open_tag()
    {
        $result = $this->isElement('<element>');

        $this->assertTrue($result);
    }

    /**
     * isElement()
     *
     * @test
     */
    public function is_element_with_close_tag()
    {
        $result = $this->isElement('</element>');

        $this->assertTrue($result);
    }

    /**
     * isElement()
     *
     * @test
     */
    public function is_not_element_with_open_comment()
    {
        $result = $this->isElement('<!--');

        $this->assertFalse($result);
    }

    /**
     * isElement()
     *
     * @test
     */
    public function is_not_element_with_close_comment()
    {
        $result = $this->isElement('-->');

        $this->assertFalse($result);
    }

    /**
     * addEntity()
     *
     * @test
     */
    public function add_entity_adds_entity()
    {
        $this->addEntity('<!ENTITY test "This is a test">');

        $this->assertEquals($this->entities, ['test' => 'This is a test']);

        $result = $this->replaceEntity('<tag>I am a tag. &test;</tag>');

        $this->assertEquals($result, '<tag>I am a tag. This is a test</tag>');
    }

    /**
     * containsEntity()
     * 
     * @test
     */
    public function contains_entity()
    {
        $result = $this->containsEntity('<tag>&n;</tag>');

        $this->assertTrue($result);
    }

    /**
     * containsEntity()
     *
     * @test
     */
    public function contains_entity_tag()
    {
        $result = $this->containsEntity('<tag>Hmm, do I conatin an entity or not?</tag>');

        $this->assertFalse($result);
    }

    /**
     * containsEntity()
     *
     * @test
     */
    public function contains_entity_with_amp()
    {
        $result = $this->containsEntity('<tag>Let\'s see if we can &trick it</tag>');

        $this->assertFalse($result);
    }

    /**
     * containsEntity()
     *
     * @test
     */
    public function contains_entity_with_semicolon()
    {
        $result = $this->containsEntity('<tag>One more; shot</tag>');

        $this->assertFalse($result);
    }

    /**
     * containsEntity()
     *
     * @test
     */
    public function contains_entity_with_amp_and_semicolon()
    { 
        $result = $this->containsEntity('<tag>I & you too bet this will get it; or will it</tag>');

        $this->assertFalse($result);
    }

    /**
     * containsEntity()
     *
     * @test
     */
    public function get_attribute_gets_attribute()
    {
        foreach ($this->xml_mock as $line) {
            $array = $this->xmlToArrays($line, 'book');
        }

        $result = $this->getAttributeFromArray($array, 'id');

        $this->assertEquals($result, 'bk101');
    }
}
