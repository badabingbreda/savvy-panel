<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlSwitch extends Control {

    public $type = 'switch';

    public $defaults = [
        "id" => "",
        "label" => "",
        "state" => false,
        "class" => null,
        "target" => null,
        "classtoggle" => null,
        "event" => 'click',
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,
    ];

    public function __( $output = '' ) {
        $settings = $this->settings;

        $class = $this->outputIf( $settings[ 'class' ] );
        $state = $settings[ "state" ] === 'true' ? "checked" : "";

        return  
        <<<EOL
        <div class="control-field {$this->type} {$class}"
        data-control-type="{$this->type}"
        data-switch-target="{$settings['target']}"
        data-switch-classtoggle="{$settings['classtoggle']}"
        data-switch-event="{$settings['event']}"
        data-switch-laststate="{$settings['state']}">
            <label>
                <input type="checkbox" 
                    id="{$settings[ "id" ]}" 
                    name="{$settings[ "id" ]}" 
                    {$state} />
                <span class="slider round"></span>
            </label>		
        </div>
        EOL;

    }
    
    private static function attribute_target_element( $target ) {
        return " data-target-element=\"{$target}\"";
    }

}