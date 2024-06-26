<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlText extends Control {

    public $type = 'text';

    public $defaults = [
        "id" => "",
        "label" => "",
        "value" => "",
        "placeholder" => "",
        "class" => null,
        "type" => "text",
        "suffix" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,
];

    public function __( $output = '' ) {
        
        $settings = $this->settings;
    
        $suffix = "";
        
        if ( $this->settings[ 'suffix' ] ) {
            $suffix = "<div class=\"suffix\">{$this->settings['suffix']}</div>";
        }

        $class = $this->outputIf( $settings[ 'class' ] );
        if ( $this->settings[ 'suffix' ] ) $class .= ' has-suffix';
    
        return  
        <<<EOL
        <div class="control-field text {$class}"
        data-control-type="text">
        <input 
            type="{$settings['type']}"
            placeholder="{$settings['placeholder']}"
            id="{$settings['id']}"
            name="{$settings[ "id" ]}" 
            value="{$settings[ "value" ]}" 
        >{$suffix}

        </div>
        EOL;
    }
}