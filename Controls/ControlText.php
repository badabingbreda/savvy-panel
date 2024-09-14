<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;
use Jawira\CaseConverter\Convert;

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
        "style" => [],
];

    public function __( $output = '' ) {
        
        $settings = $this->settings;
    
        $suffix = "";
        $style = "";
        
        if ( $this->settings[ 'suffix' ] ) {
            $suffix = "<div class=\"suffix\">{$this->settings['suffix']}</div>";
        }

        $class = $this->outputIf( $settings[ 'class' ] );
        if ( $this->settings[ 'suffix' ] ) $class .= ' has-suffix';

        if ( !empty($this->settings[ 'style']) ) {
            foreach( $this->settings[ 'style' ] as $k => $property ) {
                $style .= (new Convert( $k ))->toKebab() . ":" . $property . ";";
            }

        }
    
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
            style="{$style}"
        >{$suffix}

        </div>
        EOL;
    }
}