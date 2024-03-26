<?php
namespace SavvyPanel;

interface TabInterface {

        
    /**
     * __construct
     * 
     * constructor function, must pass config or use default will be used
     *
     * @param  mixed $config
     * @return void
     */
    public function __construct( $settings = [] ); 
    
    /**
     * init
     *
     * @param  mixed $config
     * @return void
     */
    public function init( $settings );
    
    /**
     * get_id
     * 
     * return tab id
     *
     * @return void
     */
    public function get_id();


}