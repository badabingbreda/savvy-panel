<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;
use SavvyPanel\Dashboard;

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
        "required" => false,
        "pattern" => "",
        "style" => [],
    ];

    public function enqueue_js() {
        wp_enqueue_script( "savvy-control-{$this->type}", SAVVYPANEL_URL . "js/controls/{$this->type}.js", array(), Dashboard::SAVVYPANEL_VERSION , true );
    }

    public function __( $output = '' ) {
        
        $settings = $this->settings;
    
        $suffix = "";
        $style = "";
        
        if ( $this->settings[ 'suffix' ] ) {
            $suffix = "<div class=\"suffix\">{$this->settings['suffix']}</div>";
        }

        $class = $this->outputIf( $settings[ 'class' ] );
        if ( $this->settings[ 'suffix' ] ) $class .= ' has-suffix';

        $required = "data-required=\"" .
            $this->outputTrueFalse( $this->settings[ 'required' ] ) . 
            "\""
        ;


        $style = $this->styleParser();   

        return  
        <<<EOL
        <div class="control-field {$this->type} {$class}"
        {$required}
        data-pattern-match="{$settings['pattern']}"
        data-control-type="{$this->type}">
        <input 
            type="{$settings['type']}"
            placeholder="{$settings['placeholder']}"
            id="{$settings['id']}"
            name="{$settings[ "id" ]}" 
            value="{$settings[ "value" ]}" 
            style="{$style}"
        >{$suffix}

        </div>
        EOL;
    }
}