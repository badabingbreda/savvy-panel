( function( savvyPanel ) {
    'use strict';

    var control = {};


    control.checkIsRequired = function ( notifications ) {

        var new_notifications = [];
        var password_fields = document.querySelectorAll( '.control-field.text' );

        if ( password_fields.length > 0 ) {
            for ( var i = 0; i < password_fields.length; i++ ) {
                var field = password_fields[i];
                if ( field.dataset.required === "0" ) continue;

                let controlwrapper = field.closest( '.controlwrapper');
                
                if ( controlwrapper.querySelector( 'input' ).value !== '' ) continue;

                new_notifications.push( { 
                    title : 'Field `' + controlwrapper.querySelector( '.label').innerHTML + '` needs your attention' , 
                    status : 'error',
                    description : 'This is a required field'
                } );
        
            }
        }

        return [ ...notifications , ...new_notifications ];
    }    

    control.checkPattern = function(notifications ) {
        var new_notifications = [];
        var fields = document.querySelectorAll( '.control-field.text' );

        if ( fields.length > 0 ) {
            for ( var i = 0; i < fields.length; i++ ) {
                var field = fields[i];
                if ( field.dataset.patternMatch === "" ) continue;

                let controlwrapper = field.closest( '.controlwrapper');
                let value = controlwrapper.querySelector( 'input' ).value;
                if ( value == '' ) continue;

                var re = new RegExp( field.dataset.patternMatch );
                var ok = re.exec( value );

                if ( !ok ) {
                    new_notifications.push( { 
                        title : 'Field `' + controlwrapper.querySelector( '.label').innerHTML + '` does not match its type' , 
                        status : 'error',
                        description : ''
                    } );
                }

            }
        }
        return [ ...notifications , ...new_notifications ];
    }

    control.handleChange = function ( savvy ) {
        var password_fields = document.querySelectorAll( '.control-field.text' );
        if ( password_fields.length > 0 ) {
            for ( var i = 0; i < password_fields.length; i++ ) {
                var field = password_fields[i];
                if ( field.dataset.required === "0" ) continue;

                let controlwrapper = field.closest( '.controlwrapper');
                
                controlwrapper.querySelector( 'input' ).addEventListener( 'keyup' , 
                    function(e) {
                        if ( control.hasvalue( e.target ) ) {
                            controlwrapper.classList.remove( 'control-error' );
                        } else {
                            controlwrapper.classList.add( 'control-error' );
                        }
                    }.bind( savvy ) );

                // trigger the event so this control gets checked immediatly
                controlwrapper.querySelector( 'input' ).dispatchEvent( new Event('keyup') );
            }
        }
    }

    control.hasvalue = function( elem ) {
        return ( elem.value !== '' );
    }


    // add change listeners on required fields
    savvyPanel.add_action( 'savvyInit' , control.handleChange.bind( savvyPanel ) , 10 );
    savvyPanel.add_filter( 'savvyTestFields' , control.checkIsRequired , 10 );
    savvyPanel.add_filter( 'savvyTestFields' , control.checkPattern , 10 );


}) ( window.savvyPanel );