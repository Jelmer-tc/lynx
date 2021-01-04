<?php

namespace lynx\fields;

use Craft;
use craft\base\Field;
use craft\base\ElementInterface;

/**
 * Class LinkField
 * @package typedlinkfield\fields
 */
class LynxField extends Field
{
    public $raw_value;
    public $testAttribute_1;
    public $testAttribute_2;
    
    /* Notes:
    - normalizeValue receives into the $value parameter, the value that serializeValue returns.
    - serializeValue should return the value that you want to retrieve from the database.
    - Under water normalizeValue and Serialize value are subject to JSON-encoding
    
    
/**
* @param $value
* @param ElementInterface|null $element
* @return Link
*/
public function normalizeValue($value, ElementInterface $element = null) {
    return array(
        'raw_value' => $value,
        'testAttribute_1' => "Hello_",
        'testAttribute_2' => "World_",
    );
}
    
//public function serializeValue($value, ElementInterface $element = null) {
//    $serializedValue = array(
//        'raw_value' => $value,
//        'testAttribute_1' => "Hello_",
//        'testAttribute_2' => "World_",
//    );
//}


    
    /**
    * @param Link $value
    * @param ElementInterface|null $element
    * @return string
    * @throws Throwable
    */
    public function getInputHtml($value, ElementInterface $element = null): string {
        return Craft::$app->getView()->renderTemplate('lynx/_input', [
            'test' => "This is a test",
        ]);
    }
    
    // Static methods
    // --------------
    
    /**
    * @inheritDoc
    */
    static public function displayName(): string {
        return Craft::t('lynx', 'Lynx');
    }
}