<?php

namespace lynx\fields;

use Craft;
use craft\base\Field;
use craft\base\ElementInterface;
use craft\elements\Entry;
use yii\db\Schema;

/**
 * Class LynxField
 * @package lynx\fields
 */
class LynxField extends Field
{
    public $lynx;

//    public $linkType;
//    public $entry;
//    public $text;
//    public $title;
//    public $inputUrl;
//    public $url;
//    public $target;
    
    /* Notes:
    - normalizeValue receives into the $value parameter, the value that serializeValue returns.
    - serializeValue should return the value that you want to retrieve from the database.
    - Under water normalizeValue and Serialize value are subject to JSON-encoding
    
    
/**
* @param $value
* @param ElementInterface|null $element
* @return array
*/
public function normalizeValue($value, ElementInterface $element = null) {
//    return $value;
//    var_dump($value);
//    die();

    
    
    if(!is_array($value)) {
        $values = json_decode($value, true);
    } else {
        $values = $value;
    }
    
//   var_dump($values);
//   die();
    
    $lynx = [];
    for($i=0; $i<count($values); $i++) {
        
    //    var_dump($values);
    //    die();
        
        $lynx[$i]['linkType'] = $values[$i]['linkType'];
        $lynx[$i]['entry'] = $values[$i]['entry'];
        $lynx[$i]['text'] = $values[$i]['text'];
        $lynx[$i]['title'] = $values[$i]['title'];
        $lynx[$i]['target'] = $values[$i]['target'];

        $lynx[$i]['url'] = $this->getUrl($values[$i]);

    }

    $this->lynx = $lynx;    
    return $lynx;
}
    
private function getUrl($value) {
    
    switch($value['linkType']) {
        case 'custom':
            $url = $value['url'];
            break;
        case 'entry':
            $url = Entry::findOne(['id' => $value['entry']])->getUrl();
            break;
        default:
            $url = '#';
            break;
    }
    
    return $url;
}
    
//public function serializeValue($value, ElementInterface $element = null) {
//    $serializedValue = array(
//        'raw_value' => $value,
//        'testAttribute_1' => "Hello_",
//        'testAttribute_2' => "World_",
//    );
//}


    
    /**
    * @param LynxField $value
    * @param ElementInterface|null $element
    * @return string
    * @throws Throwable
    */
    public function getInputHtml($value, ElementInterface $element = null): string {
//        var_dump($value);
//        die();
        
        $template_settings = [];
        
        if($value) {
//            var_dump($value);
//            die();
            for($index=0; $index<count($value); $index++) {
                $template_settings['lynx'][$index] = $this->templateSettingsTemplate($index);
                
                if(isset($value[$index]['linkType']['value'])) { $template_settings['lynx'][$index]['typeSelectSettings']['value'] = $value[$index]['linkType']['value']; }
                
                if(isset($value[$index]['entry']['value'])) { $template_settings['lynx'][$index]['entrySelectSettings']['value'] = $value[$index]['entry']['value']; }
                
                if(isset($value[$index]['text']['value'])) { $template_settings['lynx'][$index]['textInputSettings']['value'] = $value[$index]['text']['value']; }
                
                if(isset($value[$index]['title']['value'])) { $template_settings['lynx'][$index]['titleInputSettings']['value'] = $value[$index]['title']['value']; }

                if(isset($value[$index]['url']['value'])) { $template_settings['lynx'][$index]['urlInputSettings']['value'] = $value[$index]['url']['value']; }
                
                if(isset($value[$index]['target']['value'])) { $template_settings['lynx'][$index]['targetCheckboxSettings']['checked'] = true; }
                
//                var_dump($template_settings);
//                die();
            }
        } else {
            $template_settings['lynx'][] = $this->templateSettingsTemplate(0);
        }
        
//        var_dump($template_settings);
//        die();
        
        return Craft::$app->getView()->renderTemplate('lynx/_input', $template_settings);
    }
    
    private function templateSettingsTemplate($index) {
        return [
            'typeSelectSettings' => [
                'id'            => $this->handle . '-type-value',
                'name'          => $this->handle . '['.$index.'][linkType][value]',
                'options'       => [
                    'custom' => Craft::t('lynx', 'Custom'),
                    'entry' => Craft::t('lynx', 'Entry'),
                    'asset' => Craft::t('lynx', 'Asset'),
                ]
                //'value' => ...
            ],

            'entrySelectSettings' => [
                'id'            => $this->handle . '-entry-value',
                'name'          => $this->handle . '['.$index.'][entry][value]',
                'elementType'   => Entry::class,
                'limit'         => 1,
            //                'sources'       => '*', //Allowed sources to fetch entries from
            //                'elements'      => null, //TODO: Set values if this is not the first time entry is being saved
            //                'criteria'      => [      //TODO: Find out if this is important
            //                    'siteId'         => null,
            //                    'enabledForSite' => null,
            //                    'status'         => null,
            //                ],
            //                'storageKey'    => 'field.' . $this->handle, //TODO: Find out if this is important
            ],

            'textInputSettings' => [
                'id'            => $this->handle . '-text-value',
                'name'          => $this->handle . '['.$index.'][text][value]',
                'label'         => Craft::t('lynx', 'Custom text'),
                //'value' => ...
            ],

            'titleInputSettings' => [
                'id'            => $this->handle . '-title-value',
                'name'          => $this->handle . '['.$index.'][title][value]',
                'label'         => Craft::t('lynx', 'Custom title'),
                //'value' => ...
            ],

            'urlInputSettings' => [
                'id'            => $this->handle . '-url-value',
                'name'          => $this->handle . '['.$index.'][url][value]',
                'label'         => Craft::t('lynx', 'Custom url'),

                //'value' => ...
            ],

            'targetCheckboxSettings' => [
                'id'            => $this->handle . '-target-value',
                'name'          => $this->handle . '['.$index.'][target][value]',
                'value'         => '_blank',
                'label'         => Craft::t('lynx', 'Open in new tab'),
                //'checked' => ...
            ],
            
        ];
    }
    
    /**
    * Get Content Column Type
    * Used to set the correct column type in the DB
    * @return string
    */
    public function getContentColumnType(): string {
        return Schema::TYPE_JSON;
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