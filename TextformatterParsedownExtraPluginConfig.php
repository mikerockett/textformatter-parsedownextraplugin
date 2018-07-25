<?php

/**
 * TextformatterParsedownExtraPlugin
 *
 * @processwire 2.8+
 * @author: Mike Rockett
 * @license ISC
 */

class TextformatterParsedownExtraPluginConfig extends ModuleConfig
{
    /**
     * Get default configuration, automatically passed to input fields.
     * @return array
     */
    public function getDefaults()
    {
        return [
            'html5ElementSuffix' => true,
            'abbreviations' => '',
            // 'links' => [], #reserved
            'linkAttributes' => '',
            'externalLinkAttributes' => '',
            'imageAttributes' => '',
            'externalImageAttributes' => '',
            'codeClass' => 'language-%s',
            // 'codeText' => null, #reserved
            // 'codeBlockText' => null, #reserved
            'codeBlockAttrParent' => false,
            'tableClass' => null,
            'tableAlignClass' => null,
            'footnoteLinkId' => 'fn:%s',
            'footnoteBacklinkId' => 'fnref%s:%s',
            'footnoteClass' => 'footnotes',
            'footnoteLinkClass' => 'footnote-ref',
            'footnoteBacklinkClass' => 'footnote-backref',
            'footnoteLinkText' => null,
            'footnoteBacklinkText' => '&#8617;',
            'setUrlsLinked' => true,
            'setBreaksEnabled' => false,
        ];
    }

    /**
     * Render input fields on config Page.
     * @return Inputfields
     */
    public function getInputFields()
    {
        // Start inputfields
        $inputfields = parent::getInputfields();

        // Information
        $inputfields->add($this->buildInputField('InputfieldMarkup', array(
            'id' => 'information',
            'value' =>
                '<p><b>TextformatterParsedownExtraPlugin</b> is powered by <a href="https://github.com/tovic/parsedown-extra-plugin" title="Open project page in a new tab" target="_blank">ParsedownExtraPlugin</a>, which gives a boost of extra functionality to your markdown.</p>'.
                '<p>To add classes, IDs, and other attributes to an element that is not a paragraph, simply suffix that element with <code>{.class #id}</code> for classes and IDs or <code>{attr=something}</code> for attributes. For example, end a heading like this: <code>## Heading {.clause}</code>, or a link like this <code>[Link](http://url/) {.link}</code>. <a href="https://github.com/tovic/parsedown-extra-plugin#advance-attribute-parser">Learn more about this syntax</a>.</p>'.
                '<p>For code blocks, you may now use dot notation (<code>```.php html</code>) to set the language and add a class, or even specify your own class and ID (<code>```{.code-block #baz}</code>). <a href="https://github.com/tovic/parsedown-extra-plugin#code-block-class-without-language--prefix">Learn more about this syntax</a>.</p>'.
                '<p><b>You may configure the plugin below:</b></p>',
            'collapsed'=>Inputfield::collapsedNo,
        )));

        // General Options fieldset
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('General Options'),
        ]);

        // HTML5 Element Suffix
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'html5ElementSuffix',
            'label' => $this->_('Element Suffix'),
            'label2' => $this->_('Use HTML5 element suffix (recommended)'),
            'notes' => $this->_("**[Void elements](https://html.spec.whatwg.org/multipage/syntax.html#void-elements)** are those that do not require a closing/end tag because they never contain content.\nThe XHTML doctype uses ' />' as a suffix, and HTML5 uses '>'."),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Automatic Line Breaks
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'setBreaksEnabled',
            'label' => $this->_('Line Breaks'),
            'label2' => $this->_('Enable automatic line breaks on lines without 2 trailing spaces'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => false,
        ]));

        // Predefined Abbreviations
        $fieldset->add($this->buildInputField('InputfieldTextarea', [
            'name+id' => 'abbreviations',
            'label' => $this->_('Predefined Abbreviations'),
            'description' => $this->_('If you would like the module to automatically wrap specific abbreviations in an `abbr` element, specify those below in the format `**SHORT=Long**`; one on each line:'),
            'notes' => $this->_('You can also use a colon as a separator, and either separator may be surrounded by whitespace.'),
            'collapsed' => Inputfield::collapsedNever,
        ]));

        $inputfields->add($fieldset);

        // Images and Links
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Images and Links'),
            'description' => $this->_('Use format `**name=value**` and separate each with a comma.'),
        ]);

        // Image Attributes
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'imageAttributes',
            'label' => $this->_('Image Attributes'),
            'description' => $this->_('Add the following attributes to **all** images.'),
            'notes' => $this->_('**Example:** class=my-image, alt=My Image'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // External Image Attributes
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'externalImageAttributes',
            'label' => $this->_('External Image Attributes'),
            'description' => $this->_('Add the following attributes to **external** images.'),
            'notes' => $this->_('**Example:** class=external'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Link Attributes
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'linkAttributes',
            'label' => $this->_('Link Attributes'),
            'description' => $this->_('Add the following attributes to **all** links.'),
            'notes' => $this->_('**Example:** class=my-link, data-plugin=true'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // External Link Attributes
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'externalLinkAttributes',
            'label' => $this->_('External Link Attributes'),
            'description' => $this->_('Add the following attributes to **external** links.'),
            'notes' => $this->_('**Example:** target=_blank, rel=nofollow'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // URL Linking
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'setUrlsLinked',
            'label' => $this->_('URL Linking'),
            'label2' => $this->_('Enable automatic linking of URLs'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));


        $inputfields->add($fieldset);

        // Code Blocks
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Code Blocks'),
        ]);

        // Code Block Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'codeClass',
            // 'label' => $this->_('Class'),
            'skipLabel' => Inputfield::skipLabelHeader,
            'description' => $this->_('Use the following class for code blocks:'),
            'notes' => $this->_('**Note:** `%s` is a placeholder for the name of the language. Default is `language-%s` (blank is also default).'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Use code attributes on parent (<pre>)
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'codeBlockAttrParent',
            'label' => $this->_('Parent Attributes'),
            'label2' => $this->_('Place code block attributes on their parent element'),
            'notes' => $this->_("When enabled, attributes given to `<code>` elements are instead placed on their parent `<pre>` elements."),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        $inputfields->add($fieldset);

        // Tables
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Tables'),
        ]);

        // Table Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'tableClass',
            'label' => $this->_('Table Class'),
            'description' => $this->_('Use the following class for tables:'),
            'notes' => $this->_("If you specify an integer, the `border` attribute will be set.\nOtherwise, your input will be applied to the `class` attribute."),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Table Alignment Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'tableAlignClass',
            'label' => $this->_('Table Alignment Class'),
            'description' => $this->_('Use the following class for table-alignment:'),
            'notes' => $this->_('**Example:** `text-%s`, where `%s` is the alignment of the table.'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        $inputfields->add($fieldset);

        // Footnotes
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Footnotes'),
        ]);

        // Footnote Block Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteClass',
            'label' => $this->_('Block Class'),
            'description' => $this->_('Use the following class for the footnotes block:'),
            'notes' => $this->_('**Example:** `cite-refs`'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
        ]));

        // Footnote Link ID Format
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteLinkId',
            'label' => $this->_('Link ID Format'),
            'description' => $this->_('Use the following ID format for footnote references:'),
            'notes' => $this->_('**Example:** `cite_note:%s`, where `%s` is the footnote reference number.'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Footnote Backlink ID Format
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteBacklinkId',
            'label' => $this->_('Backlink ID Format'),
            'description' => $this->_('Use the following ID format for footnote backlink references:'),
            'notes' => $this->_('**Example:** `cite_ref:%s`, where `%s` is the footnote backlink reference number.'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Footnote Link Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteLinkClass',
            'label' => $this->_('Link Class'),
            'description' => $this->_('Use the following class for footnote links:'),
            'notes' => $this->_('**Example:** `cite-ref`'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Footnote Backlink Class
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteBacklinkClass',
            'label' => $this->_('Backlink Class'),
            'description' => $this->_('Use the following class for footnote backlinks:'),
            'notes' => $this->_('**Example:** `cite-backref`'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Footnote Link Text
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteLinkText',
            'label' => $this->_('Link Text'),
            'description' => $this->_('Use the following format for footnote links:'),
            'notes' => $this->_('**Example:** `[%s]`, where `%s` is the footnote reference number.'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        // Footnote Backlink Text
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'footnoteBacklinkText',
            'label' => $this->_('Backlink Text'),
            'description' => $this->_('Use the following format for footnote backlinks:'),
            'notes' => $this->_('**Example:** `<i class="icon icon-back"></i>` - default is ↩ (`&amp;#8617;`)'),
            'collapsed' => Inputfield::collapsedNever,
            'monospace' => true,
            'columnWidth' => 50,
        ]));

        $inputfields->add($fieldset);

        return $inputfields;
    }

    /**
     * Given a fieldtype, create, populate, and return an Inputfield
     * @param  string       $fieldNameId
     * @param  array        $meta
     * @return Inputfield
     */
    protected function buildInputField($fieldNameId, $meta)
    {
        $field = wire('modules')->get($fieldNameId);
        foreach ($meta as $metaNames => $metaInfo) {
            $metaNames = explode('+', $metaNames);
            foreach ($metaNames as $metaName) {
                $field->$metaName = $metaInfo;
                if ($metaName === 'monospace' && $metaInfo === true) {
                    $field->setAttribute('style',
                        "font-family:Menlo,Monaco,'Andale Mono','Lucida Console','Courier New',monospace;".
                        "font-size:14px;padding:4px"
                    );
                }
            }
        }
        return $field;
    }
}
