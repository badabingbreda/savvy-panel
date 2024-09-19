<?php
namespace SavvyPanel\Controls;

use SavvyPanel\Control;
use SavvyPanel\Dashboard;

class ControlSection extends Control {

    public $type = 'section';

    public $defaults = [
        "id" => "",
        "label" => "",
        "class" => null,
        "dashboard" => null,
        "tab" => null,
        "section" => null,
        "collapseable" => true,
        "collapsed" => false,
        "priority" => 10,
    ];

    public function enqueue_js() {
        wp_enqueue_script( 'savvy-control-section', SAVVYPANEL_URL . 'js/controls/section.js', array(), Dashboard::SAVVYPANEL_VERSION , true );
    }


    public function controlwrapper( $output ) {
        $collapsable = "";
        $collapsed = "";

        if ( $this->settings[ 'collapseable' ] ) {
            $collapsable = "data-section-can-collapse=\"" .
                $this->outputTrueFalse( $this->settings[ 'collapseable' ] ) . 
                "\""
            ;
            $collapsed = "data-section-collapsed=\"" .
                $this->outputTrueFalse( $this->settings[ 'collapsed' ] ) .
                "\""
            ;
        }

        return $output .=<<<EOL
        <div class="{$this->type}" id="{$this->settings['id']}" {$collapsable}{$collapsed}>
        <h3>
            {$this->settings[ 'label' ]}
            <div sp-collapse></div>
        </h3>
        {$this->__($output)}
        </div>
        EOL;

    }

    public function __( $output = '' ) {

        // return null if someone is trying to be smart
        if ( $this->settings[ 'section' ] === $this->settings[ 'id' ] ) return '';
        
        return apply_filters( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs/' . $this->settings[ 'tab' ] . '/' .  $this->settings[ 'id' ]. '/controls' , '' );
    }
}