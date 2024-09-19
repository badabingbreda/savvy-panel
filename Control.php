<?php
namespace SavvyPanel;

use Jawira\CaseConverter\Convert;

abstract class Control implements ControlInterface {

    public $type;

    public $defaults = [];

    public $settings = [];

    public function __construct( $settings = [] ){
        $this->init( $settings );
        $this->prepare();
    }
    
    /**
     * init
     * 
     * run all major stuff here
     *
     * @param  mixed $settings
     * @return void
     */
    public function init( $settings ) {
        $this->settings = $this->parse_settings( $settings , $this->defaults );
        // maybe enqueue scripts and styles
        $this->scripts();
        if ( $this->set_and_value( 'dashboard') && $this->set_and_value( 'tab' ) && $this->set_and_value( 'priority' ) ) {
            if ( $this->set_and_value( 'section' ) ) {
                add_filter( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs/' . $this->settings[ 'tab' ] . '/' .  $this->settings[ 'section' ]. '/controls' , array($this , 'controlwrapper' ) , $this->settings[ 'priority' ] , 1 );
            } else {
                add_filter( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs/' . $this->settings[ 'tab' ] . '/controls' , array($this , 'controlwrapper' ) , $this->settings[ 'priority' ] , 1 );
            }
        }
    }

    private function set_and_value( $key ) {
        return isset( $this->settings[ $key ] ) && $this->settings[ $key ];
    }
    
    /**
     * prepare
     * 
     * potentially do more stuff here, make sure to return $this
     *
     * @return void
     */
    public function prepare() {
        return $this;
    }
    
    /**
     * parse_settings
     * 
     * merge settings with defaults
     *
     * @param  mixed $settings
     * @param  mixed $defaults
     * @return void
     */
    public function parse_settings( $settings , $defaults ) {
        return wp_parse_args( 
            $settings, 
            $defaults);
    }
    
    /**
     * scripts
     * 
     * test for methods and add js/css if they do
     *
     * @return void
     */
    public function scripts() {
        // enqeue if the method has been created on the control
        if ( method_exists( get_called_class() , 'enqueue_js' ) ) {
            add_action( 'admin_enqueue_scripts' , array( $this , 'enqueue_js' ) , 10 , 1);
        }
        
        // enqueue if the method has been created on the control
        if ( method_exists( get_called_class() , 'enqueue_css' ) ) {
            add_action( 'admin_enqueue_scripts' , array( $this , 'enqueue_css' ) , 10 , 1);
        }
    }
    
    /**
     * outputIf
     *
     * @param  mixed $value
     * @return void
     */
    public function outputIf( $value ) {
        if ( $value !== null ) return $value;
        return "";
    }

    /**
     * outputTrueFalse
     *
     * Return "1" if value is true, else return "0"
     *
     * @param  mixed $value
     * @return string
     */
    public function outputTrueFalse( $value ) {
        if ( $value === true || $value == "1" ) return "1";
        return "0";
    }
    
    /**
     * __
     * 
     * return string
     *
     * @return void
     */
    public function __( $output ) {
        return $output;
    }

    public function controlwrapper( $output ) {
        return $output .=<<<EOL
            <div class="controlwrapper">
                <div class="label">{$this->settings['label']}</div>
                <div class="control">{$this->__($output)}</div>
            </div>
        EOL;
    }

    public function styleParser() {
        $style = "";
        if ( !empty($this->settings[ 'style']) ) {
            foreach( $this->settings[ 'style' ] as $k => $property ) {
                $style .= (new Convert( $k ))->toKebab() . ":" . $property . ";";
            }
        }

        return $style;
    }
    
    /**
     * _e
     * 
     * echo to browser
     *
     * @return void
     */
    public function _e( $output ) {
        echo $this->__( $output );
    }

    public function enqueue_js() {  }

    public function enqueue_css() {  }

}