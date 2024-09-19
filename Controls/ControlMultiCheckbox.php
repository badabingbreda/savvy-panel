<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlMultiCheckbox extends Control {

    public $type = 'multicheckbox';

    public $defaults = [
        "id" => "",
        "label" => "",
        "options" => "",
        "class" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,

];

    public function __( $output = '' ) {
        $settings = $this->settings;

        $return = '';

        $template = '<div data-control-type="{$this->type}"><input type="checkbox" name="'.$settings['id'].'[]" id="'.$settings['id'].'_%s" value="%s" %s %s><label for="'.$settings['id'].'_%s">%s</label></div>';

        foreach ($settings['options'] as $option) {
            $return .= sprintf($template, 
                        $option['value'], 
                        $option['value'], 
                        \checked( $option['checked'], true, false ),
                        \disabled( $option['disabled'] , true, false ),
                        $option['value'], 
                        $option['id']);
        }

        return $output .= $return;
        
    }

}