(function ($) {
    $(document).ready( function() {
        var tab = '';
        $('#adminoptions-tab').jqTabs( { duration: 200 });
        $("#adminoptions-tab .jq-tab-menu .jq-tab-title").click( function () {
            // show hash in top-bar
            window.location.hash= $(this).data("tab");
        });
        // change active tab to hash if found
        if ( window.location.hash ) {
            var url = window.location.href,
                tab = url.substring(url.indexOf('#')+1);
        } else {
                // get first tab
                tab = $( `[data-tab]:first`).data( 'tab' );
        }
        // activate the button and content
        $('[data-tab='+tab+']').addClass('active');
    });
})(jQuery);

// hex to HSL, implement later
// https://gist.github.com/xenozauros/f6e185c8de2a04cdfecf

savvyPanel = function ( settings ) {
    this.settings = settings;
}

savvyPanel.prototype = {
    FILTERS: {},
    ACTIONS: {},

    init : function() {

        const _this = this;

        this.global_actions();

        this.add_action( 'savvyInit' , this.initSwitch.bind( _this ) , 10 );
        this.add_action( 'savvyInit' , this.addSaveChangesListener.bind( _this ) , 10 );
        this.add_action( 'savvyInit' , this.setColorisSettings.bind( _this ) , 10 );

        this.add_filter( 'savvyGetControlValue' , this.getControlSwitchValue.bind( _this ) , 10 );
        this.add_filter( 'savvyGetControlValue' , this.getControlColorValue.bind( _this ) , 10 );
        this.add_filter( 'savvyGetControlValue' , this.getControlTextValue.bind( _this ) , 10 );
        this.add_filter( 'savvyGetControlValue' , this.getControlSliderValue.bind( _this ) , 10 );

        this.do_action( 'savvyInit' , _this );
    },
    
    setColorisSettings : function( savvy ) {
        if ( typeof window.Coloris !== 'function' ) return;
        window.Coloris({
            themeMode: 'dark',
            alpha: false
        });
    },
    
    initSwitch : function( savvy ) {
        document.querySelectorAll( '.control-field.switch input' ).forEach( 
            function( elem ) {
                this.initState( elem );
                elem.addEventListener( 'change' , function (e) { this.handleSwitchClick( e ); }.bind( this ) );
            }.bind( savvy )
        );
    },

    addSaveChangesListener : function( savvy ) {
        document.querySelector( '.dashboard-save-changes' ).addEventListener( 'click' , function(e) {
            let notifications = this.testFields();
            if ( notifications.length > 0 ) {
                notifications.forEach( (noti, index) => {
                    setTimeout( () => { notis.create( { title : noti.title , description : noti.description , duration: 5 ,  status: noti.status , destroyOnClick: true } ); } , index * 300 );
                });
                return;
            }
            if ( !savvy.sending ) {
                // do not allow new clicks while sending
                savvy.do_action( 'savvyUpdate' , savvy , e );
            }
        }.bind( savvy ) );
    },
    
    testFields : function( savvy ) {
        // perform tests on fields that require that and return notices
        var notifications = this.apply_filters( 'savvyTestFields' , [] );
        return notifications;
    },

    handleSwitchClick : function ( e ) {
        const switchElem = e.target.closest( '.control-field' );
        let target = switchElem.dataset.switchTarget,
            classtoggle = switchElem.dataset.switchClasstoggle;

        // only toggle class if both target and classtoggle have been set
        // otherwise just toggle the switch visually
        if ( target && classtoggle ) document.querySelector( target ).classList.toggle( classtoggle );

    },

    initState : function( elem ) {
        const switchElem = elem.closest( '.control-field' );

        let target = switchElem.dataset.switchTarget,
            classtoggle = switchElem.dataset.switchClasstoggle,
            laststate = switchElem.dataset.switchLaststate;
        /**
         * check if target and classtoggle have been set
         * if entered, when using the control it toggles a class and 
         * can be used to show/hide other sections/controls
         */
        if ( target && classtoggle ) {
            if ( laststate === 'true' ) { 
                document.querySelector( target ).classList.remove( classtoggle );
            } else {
                document.querySelector( target ).classList.add( classtoggle );
            }
        }
    },

    collectSettings : function( ) {
        const tabs = document.querySelectorAll( '.jq-tab-content' );
        let settings = {};
        // go over the tabs
        tabs.forEach( (tab) => {
            // go over the controls
            tab.querySelectorAll( '.control-field' ).forEach( (control) => {
                var currentValue = this.apply_filters( 'savvyGetControlValue' , null , control , control.dataset.controlType );
                settings = {...settings , ...currentValue };
            });
        });

        return settings;
    },

	asFormData : function( data ) {
		let formData = '';
		for (var key in data ) {
			formData += `${key}=${data[key]}&`
		}
		return formData;

	},    

    getControlSwitchValue : function ( value , elem, type ) {
        // return the value early
        if ( type !== 'switch' ) return value;
        return { [elem.querySelector( 'input' ).id] : elem.querySelector( 'input' ).checked };
    },

    getControlColorValue : function ( value , elem, type ) {
        // return the value early
        if ( type !== 'color' ) return value;
        return { [elem.querySelector( 'input' ).id] : elem.querySelector( 'input' ).value };
    },

    getControlTextValue : function ( value , elem, type ) {
        // return the value early
        if ( type !== 'text' ) return value;
        return { [elem.querySelector( 'input' ).id] : elem.querySelector( 'input' ).value };
    },

    getControlSliderValue : function ( value , elem, type ) {
        // return the value early
        if ( type !== 'slider' ) return value;
        return { [elem.querySelector( 'input' ).id] : elem.querySelector( 'input' ).value };
    },


   /**
     * http://undolog.com/wordpress-actions-and-filters-in-javascript/
     * 
     * @param {*} type 
     * @param {*} tag 
     * @param {*} function_to_add 
     * @param {*} priority 
     */
   _add: function( type, tag, function_to_add, priority )
   {
     var lists = ( 'filter' == type ) ? this.FILTERS : this.ACTIONS;
 
     // Defaults
     priority = ( priority || 10 );
 
     if( !( tag in lists ) ) {
       lists[ tag ] = [];
     }
 
     if( !( priority in lists[ tag ] ) ) {
       lists[ tag ][ priority ] = [];
     }
 
     lists[ tag ][ priority ].push( {
       func : function_to_add,
       pri  : priority
     } );
 
   }, 

   _do :function( type, args ) {
       var hook, lists = ( 'action' == type ) ? this.ACTIONS : this.FILTERS;
       var tag = args[ 0 ];

       if( !( tag in lists ) ) {
           return args[ 1 ];
       }

       // Remove the first argument
       [].shift.apply( args );

       for( var pri in lists[ tag ] ) {

       hook = lists[ tag ][ pri ];

       if( typeof hook !== 'undefined' ) {

           for( var f in hook ) {
           var func = hook[ f ].func;

           if( typeof func === "function" ) {

               if( 'filter' === type ) {
                   args[ 0 ] = func.apply( null, args );
               } else {
                   func.apply( null, args );
               }
           }
           }
       }
       }

       if( 'filter' === type ) {
           return args[ 0 ];
       }

   },

   add_filter: function( hook , callback , priority ) {
       this._add( 'filter' , hook , callback , priority );
   },

   add_action: function( hook , callback , priority ) {
       this._add( 'action' , hook , callback , priority );
   },

   apply_filters : function( hook , value, varargs ) {
       return this._do( 'filter', arguments );
   },

   do_action : function( hook , args ) {
       this._do( 'action', arguments );
   },

   // todo
   remove_filter: function( hook , callback , priority ) {

   },

   // todo
   remove_action: function ( hook , callback , priority ) {

   },

   global_actions: function( ) {
       if ( typeof document.savvyPanelActions !== 'undefined' ) {
           document.savvyPanelActions.forEach( action => {
               if ( 
                   action.id == this.settings?.id && 
                   action?.hook || false && 
                   ( typeof action?.callback || false ) == 'function' 
               ) {
                   this.add_action( action.hook , action.callback.bind( this ) , action?.priority || 10 );
               }
           });
       }
       if ( typeof document.savvyPanelFilters !== 'undefined' ) {
           document.savvyPanelFilters.forEach( action => {
               if ( 
                   action.id == this.settings?.id && 
                   action?.hook || false && 
                   ( typeof action?.callback || false ) == 'function' 
               ) {
                   this.add_filter( action.hook , action.callback.bind( this ) , action?.priority || 10 );
               }
           });
       }
   },

}

window.savvyPanel = new savvyPanel( {} );

window.onload = function( event ) {
    window.savvyPanel.init();
}