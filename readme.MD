# SavvyPanel

SavvyPanel is a helper library to create more compelling setttings dashboards using tabs, sections and controls.

Include the library by adding it via composer:

    composer require badabingbreda/savvy-panel dev-main

It assumes a dashboard panel is added using a plugin, the constant `SAVVYPANEL_URL` will automatically be created if not defined early. Set this constant yourself to the right location if loading in another location.

## Adding Dashboard

Make sure to autoload the `vendor/autoload.php` file after installing the composer packages.


    <?php
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

            new \SavvyPanel\Controls\ControlText([
                'dashboard' => 'beavercss',
                'tab' => 'viewport',
                'section' => 'breakpoints',
                'id' => 'media-breakpoint-s',
                'label' => "Breakpoint Small",
                'suffix' => "px",
                'value' => \get_option( 'media-breakpoint-s' , 640 ),
            ]);


        }
    }

    new PluginDashboard();


## Sending updated settings

You can hook into the Savvy Panel update action by adding javascript that loads in the header, like so:

    <?php
    class ScriptStyle {

        public function __construct() {
            add_action( 'admin_enqueue_scripts' , __CLASS__ . '::admin_files' , 10 , 1 );
        }
        
        public static function admin_files() {
            wp_enqueue_script( 'savvypanel-handler', plugin_url( '/' , __FILE__ ) . 'js/savvy-panel.js', null, '1.0.0', false );
        }
    }

    new ScriptStyle();

You could add additional checks to your loading strategy to make sure they are loading on the admin and page only.

In your `savvy-panel.js` file, add your callback for the savvy hooks, like so:

    document.savvyPanelActions = [
        { 
            hook : 'savvyUpdate',
            priority : 10,
            callback: ( savvy ) => {

                savvy.sending = true;

                const settings = savvy.collectSettings();
        
                // use fetch
                fetch( SAVVYPANEL_LOCAL.admin_ajax_url + `?action=savvypanel_update`,
                {
                    method: 'POST',
                    headers: { 
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': SAVVYPANEL_LOCAL._wpnonce,
                    },
                    body: savvy.asFormData( settings ),
                } )
                .then( ( response ) => response.json() )
                .then( ( data ) => {
                        savvy.sending = false;
                        data.notifications.forEach( (noti, index) => {
                            setTimeout( () => { notis.create( { title : noti.title , description : noti.description , duration: 5 ,  destroyOnClick: true } ); } , index * 300 );
                        });
                })
                .catch( (error) => {
                    savvy.sending = false;
                    console.log( 'I messed up ' , error );
                } );
            }
        }
    ];

In this example, the callback will be triggered when the "Save Changes" button is clicked on the panel. The `savvy` variable is the instance of the Savvy Panel that exists in your browser and can be used for collecting the Settings across all tabs and formatting them as form data. Also note that you can use the `SAVVYPANEL_LOCAL` variable, which is used to localize the savvy script with the admin-ajax.php url and a server generated nonce.

In this callback, the action `savvypanel_update` is used and this is something you will need to register yourself within your plugin, using

    add_action( 'wp_ajax_savvypanel_update' , 'your_custom_callback' , 10 , 1 );

In there, make sure to check the nonce before anything else:

    function your_custom_callback() {

        if ( !wp_verify_nonce( $_SERVER[ 'HTTP_X_WP_NONCE' ] , 'savvypanel_update_settings' ) ) {
            return wp_encode_json( [ 
                'success' => false, 
                'notifications' => [
                    [ 
                        'success' => false,
                        'title' => 'Nonce Failed',
                        'description' => 'Unable to verify nonce or failed.',
                    ]
                ],
            ] );
            wp_die();
        }

        //
        // rest of your code
        //

        return wp_encode_json( [ 
            'success' => true, 
            'notifications' => [
                    [ 
                        'success' => true,
                        'title' => 'Well done!',
                        'description' => 'Settings have been updated. Feel free to make some more!',
                    ]
            ],
        ] );
        
        wp_die();
    }