<?php

namespace Petrol\Core\Helpers\Traits;

trait XmlParser
{
    /**
     * Close tag for xmlArray().
     *
     * @var string
     */
    private $close_tag;

    /**
     * If comment tag is open, will be set to true.
     *
     * @var bool
     */
    private $comment_status = false;

    /**
     * If true, xml entities will be converted to values.
     *
     * @var bool
     */
    private $convert_entities = true;

    /**
     * True if document root tag has been read.
     *
     * @var bool
     */
    private $doc_open = false;

    /**
     * User XML Entities.
     *
     * @var array
     */
    private $entities = [];

    /**
     * Default XML entities.  Converting typically causes parsing errors.
     *
     * @var array
     */
    private $no_convert = [
        'lt' => '<',
        'gt' => '>',
        'amp' => '&',
        'apos' => "'",
        'quot' => '"',
    ];

    /**
     * Open tag for xmlArray().
     *
     * @var string
     */
    private $open_tag;

    /**
     * The text of the root tag of the xml document.
     *
     * @var string
     */
    private $root_tag;

    /**
     * True if base XML tag is open.
     *
     * @var bool
     */
    private $xml_base_open = false;

    /**
     * XML string for xmlToArrays().
     *
     * @var string
     */
    private $xml_string;

    /**
     * Convert array to data for database insertation.  Convert to array with
     * xmlToArrays() first. Keys not assigned in $columns will be passed.
     *
     * @param array $array
     * @param array $columns
     *
     * @return array
     */
    protected function arrayToData(array $array, $columns)
    {
        foreach ($array as $key => $value) {
            if (in_array($key, $columns)) {
                $this->xml_array[$key] = $value;
            }
        }

        return $this->xml_array;
    }

    /**
     * Build array from xml.
     *
     * @param xml_string $data
     *
     * @return array
     */
    protected function buildArray($data)
    {
        if (!$xml_object = simplexml_load_string($data)) {
            print_r($data);
        }

        $json = json_encode((array) $xml_object);

        $array = json_decode($json, true);

        return $array;
    }

    /**
     * Get attributes from xml in array form.
     *
     * @param array  $data
     * @param string $attribute
     *
     * @return mixed
     */
    protected function getAttributeFromArray(array $data, $attribute)
    {
        $attributes = $data['@attributes'];

        return $attributes[$attribute];
    }

    /**
     * Add entities to entities array and replace instances of them with values.
     *
     * @param string $string
     */
    protected function handleEntities($string)
    {
        if ($this->isEntityDeclaration($string)) {
            $this->addEntity($string);
        } elseif ($this->containsEntity($string)) {
            $string = $this->replaceEntity($string);
        }

        return $string;
    }

    /**
     * Set the root tag of the xml document.
     *
     * @param string $tag
     */
    protected function setRootTag($tag)
    {
        $this->root_tag = $tag;
    }

    /**
     * Convert XML tree section to standard array.  Set array root tag with method
     * setRootTag().  Attempting to convert an entire XML file into an array will
     * probably overload your system.  Beware.
     *
     * @param string $string
     * @param string $tag    [the tag at the base of the array]
     *
     * @return mixed
     */
    protected function xmlToArrays($string, $tag)
    {
        if (!isset($this->open_tag) || !isset($this->close_tag)) {
            $this->setTags($tag);
        }

        if ($this->convert_entities) {
            $string = $this->handleEntities($string);
        }

        $string = html_entity_decode($string, ENT_QUOTES, 'utf-8');

        if (!$this->isElement($string)) {
            return;
        }

        if (strpos($string, $this->open_tag) !== false) {
            $this->xml_base_open = true;
        }

        if ($this->xml_base_open) {
            $this->xml_string .= trim($string);

            if (strpos($string, $this->close_tag) !== false) {
                $array = $this->buildArray($this->xml_string);

                $this->xml_string = '';

                return $array;
            }
        }
    }

    /**
     * Convert xml to data for database insertation. Keys not assigned in $columns
     * will be passed.
     *
     * @param string $string  [file line]
     * @param string $tag     [taga at base of data]
     * @param array  $columns
     *
     * @return mixed
     */
    protected function xmlToData($string, $tag, $columns)
    {
        $array = $this->xmlToArrays($string, $tag);

        if (is_null($array)) {
            return;
        }

        return $this->arrayToData($array, $columns);
    }

    /**
     * Add entity name and value to entities array.
     *
     * @param string $string
     */
    private function addEntity($string)
    {
        $entity = $this->parseEntityDeclaration($string);

        $this->entities[$entity['name']] = $entity['value'];
    }

    /**
     * Return true if string contains an entity.
     *
     * @param string $string
     *
     * @return bool
     */
    private function containsEntity($string)
    {
        if (preg_match('/&[^\s]*;/s', $string)) {
            return true;
        }

        return false;
    }

    /**
     * Converts entity &ent; to string ent.
     *
     * @param  string $entity
     * @return string
     */
    private function entityToString($entity)
    {
        return str_replace('&', '', str_replace(';', '', $entity));
    }

    /**
     * True if string contains closing comment tag(-->).
     *
     * @param string $string
     *
     * @return bool
     */
    private function hasCloseCommentTag($string)
    {
        if (strpos($string, '-->') !== false) {
            return true;
        }

        return false;
    }

    /**
     * True if string contains opeing comment tag(<!--).
     *
     * @param string $string
     *
     * @return bool
     */
    private function hasOpenCommentTag($string)
    {
        if (strpos($string, '<!--') !== false) {
            return true;
        }

        return false;
    }

    /**
     * Returns true if element is not comment and the root tag has been read.
     *
     * @param [type] $string [description]
     *
     * @return bool [description]
     */
    private function isElement($string)
    {
        if ($this->doc_open === false && strpos($string, $this->makeOpenTag($this->root_tag)) !== false) {
            $this->doc_open = true;
        }

        if ($this->hasCloseCommentTag($string)) {
            $this->comment_status = false;

            return false;
        } elseif ($this->hasOpenCommentTag($string)) {
            $this->comment_status = true;

            return false;
        }

        if ($this->comment_status) {
            return false;
        }

        if ($this->doc_open) {
            return true;
        }

        return false;
    }

    /**
     * Return true if string is an entity declaration.
     *
     * @param string $string
     *
     * @return bool
     */
    private function isEntityDeclaration($string)
    {
        if (strpos($string, '!ENTITY')) {
            return true;
        }

        return false;
    }

    /**
     * Make a closing tag.
     *
     * @param string $tag [tag text]
     *
     * @return string
     */
    private function makeCloseTag($tag)
    {
        return '</'.$tag.'>';
    }

    /**
     * Make an opening tag. Ending '>' left off to account for attributes.
     *
     * @param string $tag [tag text]
     *
     * @return string
     */
    private function makeOpenTag($tag)
    {
        return '<'.$tag;
    }

    /**
     * Parse an internal entity declaration.
     *
     * @param string $entity [<!ENTITY name "entity_value">]
     *
     * @return array
     */
    private function parseEntityDeclaration($entity)
    {
        $name = explode(' ', $entity)[1];

        preg_match('/"([^"]+)"/', $entity, $matches);

        $value = $matches[1];

        return [
            'name' => $name,
            'value' => $value,
        ];
    }

    /**
     * Build an array of entities, replace entity name with value.
     *
     * @param string $string [a line form an xml file]
     *
     * @return string
     */
    private function replaceEntity($string)
    {
        $pattern = '/&[^\s]*;/s';

        preg_match($pattern, $string, $match);

        $match = $this->entityToString($match[0]);

        if (!array_key_exists($match, $this->no_convert)) {
            return preg_replace($pattern, $this->entities[$match], $string);
        }
    }

    /**
     * Set open and close tag variables.
     *
     * @param string $tag
     */
    private function setTags($tag)
    {
        $this->open_tag = $this->makeOpenTag($tag);

        $this->close_tag = $this->makeCloseTag($tag);
    }
}
