<?php

/**
 * TextformatterParsedownExtraPlugin
 *
 * @processwire 2.8+
 * @author: Mike Rockett
 * @license MIT
 */

class TextformatterParsedownExtraPlugin extends Textformatter
{
    /**
     * Module constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Textformatter formatValue
     * @param $text
     */
    public function formatValue(Page $page, Field $field, &$value)
    {
        $value = $this->parsedown($value);
    }

    /**
     * Required function for obtaining module info
     * in the "Details" tab of a field.
     * @return Array
     */
    public function getModuleInfo()
    {
        return json_decode(file_get_contents(__DIR__ . '/TextformatterParsedownExtraPlugin.info.json'), true);
    }

    /**
     * Configure ParsedownExtraPlugin, parse text, and return.
     * @param  $input
     * @return string
     */
    public function parsedown($input)
    {
        // Require the three libaries
        require_once __DIR__ . '/lib/Parsedown.php';
        require_once __DIR__ . '/lib/ParsedownExtra.php';
        require_once __DIR__ . '/lib/ParsedownExtraPlugin.php';

        // Instatiate the parser
        $parser = new ParsedownExtraPlugin();

        // Set options
        // Module Config Name           Package Name                    Module default [| package default]
        // - html5ElementSuffix         element_suffix                  true | " />"
        // - abbreviations              ==                              ''
        // - linkAttributes             links_attr                      ''
        // - externalLinkAttributes     links_external_attr             ''
        // - imageAttributes            images_attr                     ''
        // - externalImageAttributes    images_external_attr            ''
        // - codeClass                  code_class                      'language-%s'
        // - codeBlockAttrParent        code_block_attr_on_parent       false
        // - tableClass                 table_class                     null
        // - tableAlignClass            table_align_class               null
        // - footnoteLinkId             footnote_link_id                'fn:%s'
        // - footnoteBacklinkId         footnote_back_link_id           'fnref%s:%s'
        // - footnoteClass              footnote_class                  'footnotes'
        // - footnoteLinkClass          footnote_link_class             'footnote-ref'
        // - footnoteBacklinkClass      footnote_back_link_class        'footnote-backref'
        // - footnoteLinkText           footnote_link_text              null
        // - footnoteBacklinkText       footnote_back_link_text         '&#8617;'

        // Element Suffix (HTML5 or XHTML)
        $parser->element_suffix = ($this->html5ElementSuffix === true) ? '>' : ' />';

        // Parse any provided property lists to an array
        // for ParsedownExtraPlugin.
        $propertyMappings = [
            'abbreviations' => ['abbreviations', "\n"], # ==
            'linkAttributes' => ['links_attr', ','],
            'externalLinkAttributes' => ['links_external_attr', ','],
            'imageAttributes' => ['images_attr', ','],
            'externalImageAttributes' => ['images_external_attr', ','],
        ];
        foreach ($propertyMappings as $config => $setting) {
            if (!empty($this->$config)) {
                $parser->{$setting[0]} = $this->parsePropertyList($this->$config, $setting[1]);
            }
        }

        // Any remaining mappings may just be set as-is
        $configMappings = [
            'codeClass' => 'code_class',
            'codeBlockAttrParent' => 'code_block_attr_on_parent',
            'tableClass' => 'table_class',
            'tableAlignClass' => 'table_align_class',
            'footnoteLinkId' => 'footnote_link_id',
            'footnoteBacklinkId' => 'footnote_back_link_id',
            'footnoteClass' => 'footnote_class',
            'footnoteLinkClass' => 'footnote_link_class',
            'footnoteBacklinkClass' => 'footnote_back_link_class',
            'footnoteLinkText' => 'footnote_link_text',
            'footnoteBacklinkText' => 'footnote_back_link_text',
        ];
        foreach ($configMappings as $config => $setting) {
            $parser->$setting = $this->$config;
        }

        // Parse input and return
        return $parser->text($input);
    }

    /**
     * Parse a property list
     * Valid separators are "=" and ":"
     * Separators may be surrounded by whitespace - this will be trimmed. Ex:
     *     MSFT=Microsoft
     *     HP: Hewlett Packard
     *     SKU = Stock Keeping Unit
     * @param  $list
     * @return Array
     */
    protected function parsePropertyList($list, $propertySeparator = "\n")
    {
        $properties = explode($propertySeparator, $list);
        foreach ($properties as $property) {
            $separator = strpbrk($property, '=:');
            list($key, $value) = explode($separator[0], $property);
            $propertiesArray[trim($key)] = trim($value);
        }

        return $propertiesArray;
    }
}
