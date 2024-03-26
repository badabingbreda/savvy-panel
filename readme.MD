# SavvyPanel

SavvyPanel is a helper library to create more compelling setttings dashboards using tabs, sections and controls.

Include the library by adding it via composer:

    composer require badabingbreda/savvypanel dev-main

It assumes a dashboard panel is added using a plugin, the constant `SAVVYPANEL_URL` will automatically be created if not defined early. Set this constant yourself to the right location if loading in another location.

## Adding Dashboard

Make sure to autoload the `vendor/autoload.php` file after installing the composer packages.


class PluginDashboard {

    public function __construct() {

        add_action( 'init' , __CLASS__ . '::init' , 10 );
    }

    public static function init() {

        new \SavvyPanel\Dashboard( [ 
            'id' => 'beavercss',
            'menu_title' => 'Beaver CSS',
            'title' => 'Beaver CSS',
            'heading' => 'Beaver CSS Settings',
            'type' => 'menu',
        ] ); 

        /**
         * 
         * Viewport
         * 
         */
        new \SavvyPanel\Tab( [
            'id' => 'viewport',
            'dashboard' => 'beavercss',     // our dashboard id
            'title' => 'Main',              // comment out this key if you don't want the title rendered above the controls
            'menu_title' => 'Viewport',
            'menu_slug' => 'viewport',
            'priority' => 10,
        ]);

        new \SavvyPanel\Controls\ControlSection([
            'id' => 'breakpoints',
            'dashboard' => 'beavercss',
            'tab' => 'viewport',
            'label' => 'Breakpoints',
        ]);

    }
}

new PluginDashboard();
