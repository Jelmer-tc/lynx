<?php

namespace lynx;

use lynx\fields\LynxField;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use yii\base\Event;

class Lynx extends \craft\base\Plugin
{
    public function init()
    {
        parent::init();
        
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function(RegisterComponentTypesEvent $event) {
                $event->types[] = LynxField::class;
            }
        );
    }
}