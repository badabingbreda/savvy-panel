<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlSubmit extends Control {

    public $type = 'submit';

    public $defaults = [
        "id" => "",
        "label" => "",
        "type" => "submit",
        "value" => "Update",
        "class" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,
    ];

    public function __( $output  ) {
        $settings = $this->settings;


        return $output .= 
        <<<EOL
        <div>
        <input type="{$settings['type']}" 
        name="update" 
        id="{$settings['id']}"
        class="{$settings['class']}" 
        value="{$settings['value']}" />
        </div>
        EOL;
    }

}