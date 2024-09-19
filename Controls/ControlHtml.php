<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlHtml extends Control {

    public $type = 'html';

    public $defaults = [
        "id" => "",
        "label" => "",
        "value" => "",
        "placeholder" => "",
        "class" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,
];

    public function __( $output = '' ) {
        
        $settings = $this->settings;
    
        $class = $this->outputIf( $settings[ 'class' ] );
    
        return  
        <<<EOL
        <div class="control-field {$this->type} {$class}"
        data-control-type="{$this->type}">
        {$this->settings['value']}
        </div>
        EOL;
    }
}