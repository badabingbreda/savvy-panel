<?php
namespace SavvyPanel;

class Tab implements TabInterface {

    protected $id;

    protected $content;

    protected $settings = [];

    public $defaults = [
        'id' => null,
        'dashboard' => null,
        'title' => null,
        'menu_title' => 'Menu Title',
        'menu_slug' => 'menu-slug',
        'priority' => 10,
    ];
    
    /**
     * __construct
     *
     * @param  mixed $settings
     * @return void
     */
    public function __construct( $settings = [] ) {
        $this->init( $settings );
    }

    public function init( $settings ) {
        $this->settings = $this->parse_settings( $settings , $this->defaults );
        add_filter( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs' , array( $this , 'get_tab_settings' ) , $this->settings[ 'priority' ] );
    }

    public function remove() {
        remove_filter( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs' , array( $this , 'get_tab_settings' ) , $this->settings[ 'priority' ] );
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

    public function get_tab_settings( $tabs ) {

        $tabs[] = [
            'title' => $this->settings['title'],
            'menu_title' => $this->settings['menu_title'],
            'menu_slug' => $this->settings['menu_slug'],
            'content' => $this->tab_start() . $this->tab_content() . $this->tab_end()
        ];
        return $tabs;
    }

    private function tab_content() {
        $content = apply_filters( 'savvypanel/dashboard/' . $this->settings[ 'dashboard' ] . '/tabs/' . $this->settings[ 'id' ] . '/controls' , '' );
        return $content;
    }

    private function tab_start() {

        $title = $this->settings[ 'title' ] ? "<h3>{$this->settings['title']}</h3>" : "";

        return <<<EOL
        <div class="jq-tab-content" data-tab="{$this->settings['menu_slug']}">
        <form id="adminoptions-{$this->settings['menu_slug']}" action="#" method="post">
            {$title}
        EOL;
    }

    private function tab_end() {
        return <<<EOL
            </form>
        </div>
        EOL;

    }
    
    /**
     * get_id
     * 
     * return the id of a tab
     *
     * @return void
     */
    public function get_id() {
        return $this->id;
    }
    
    public function set_content( $string ) {
        $this->content = $string;
        return $this;
    }

    public function add_content( $string ) {
        $this->content .= $string;
        return $this;
    }
    
    
}