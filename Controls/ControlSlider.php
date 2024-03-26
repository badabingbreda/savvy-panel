<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;

class ControlSlider extends Control {

    public $type = 'slider';

    public $defaults = [
        "id" => "",
        "label" => "",
        "value" => "",
        "options" => [],
        "generate_labels" => true,
        "class" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "priority" => 10,
    ];

    public function enqueue_js() {
        \wp_enqueue_script( 'toolcool-range-slider', SAVVYPANEL_URL . '/js/toolcool-range-slider.min.js', null, \SavvyPanel\Dashboard::SAVVYPANEL_VERSION, false );
        \wp_enqueue_script( 'toolcool-binding-labels', SAVVYPANEL_URL . '/js/tcrs-binding-labels.min.js', null, \SavvyPanel\Dashboard::SAVVYPANEL_VERSION, false );
    }


    public function __( $output = '' ) {
        $settings = $this->settings;

        $class = $this->outputIf( $this->settings[ 'class' ] );

        $options = implode( ',' , $this->settings[ 'options' ] );
        $generate_labels = $this->settings['generate_labels'] ? 'true' : 'false';
        return  
        <<<EOL
        <div class="control-field {$this->type} {$class}"
        data-control-type="{$this->type}">
            <div id="{$this->settings['id']}-label" class="slider-label"></div>
            <input type="hidden" 
            id="{$this->settings['id']}" 
            name="{$this->settings['id']}" 
            value="{$this->settings['value']}" >
            <tc-range-slider 
                id="slider_{$this->settings['id']}" 
                data="{$options}" 
                value="{$this->settings['value']}" 
                generate-labels="{$generate_labels}" 
                value-label="#{$this->settings['id']}-label"></tc-range-slider>
            <script>
                {
                    let slider = document.querySelector( '#slider_{$this->settings['id']}' );
                    // listen to the change event
                    slider.addEventListener( 'change', ( e ) => {
                        // change a hidden input value to the sent event value
                        document.querySelector( '.control-field.slider [id="{$this->settings['id']}"]' ). value = e.detail.value
                    });
                }                
            </script>
        </div>
        EOL;

    }

}