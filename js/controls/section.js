( function( savvyPanel ) {
    'use strict';

    var control = {};

    control.icons = {
        'arrow-down' : `<svg class="arrow-down" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 7.33l2.829-2.83 9.175 9.339 9.167-9.339 2.829 2.83-11.996 12.17z"/></svg>`,
        'arrow-up' : `<svg class="arrow-up" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0 16.67l2.829 2.83 9.175-9.339 9.167 9.339 2.829-2.83-11.996-12.17z"/></svg>`,
    };
    
    // get the dashboard id
    var dashboardId = document.querySelector( '.adminoptions-options' ).dataset.dashboardId;
    // get the data-store-collapsed-status value
    var storeCollapsedStatus = document.querySelector( '.adminoptions-options' ).dataset.storeCollapsedStatus;

    control.sectionIcons = function( savvy ) {
        document.querySelectorAll( '[data-section-can-collapse]' ).forEach( 
            function( elem ) {
                let iconContainer = elem.querySelector( '*[sp-collapse]' );
                let clickContainer = elem.querySelector( 'h3' );
                if ( !iconContainer ) return;
                iconContainer.innerHTML = control.icons[ 'arrow-up' ];
                clickContainer.addEventListener( 'click' , function (e) { control.handleSectionCollapse( e ); }.bind( this ) );
            }.bind( savvy )
        );
    };

    control.handleSectionCollapse = function( e ) {
        // get to section container
        let container = e.target.closest( '.section' );
        container.dataset.sectionCollapsed = container.dataset.sectionCollapsed === '1' ? '0' : '1';
    };

    control.handleStoreCollapsedStatus = function( savvy ) {
        let store = {};
        document.querySelectorAll( '.section[data-section-can-collapse]' ).forEach( 
            function( elem ) {
                store = {...store , ...{ [elem.id] : elem.dataset.sectionCollapsed } };
            }
        );
        localStorage.setItem( `${dashboardId}_collapsed` , JSON.stringify( store ) || "" );
    };

    control.handleRestoreCollapsedStatus = function() {
        let store = JSON.parse( localStorage.getItem( `${dashboardId}_collapsed` ) || {} );
        document.querySelectorAll( '.section[data-section-can-collapse]' ).forEach( 
            function( elem ) {
                elem.dataset.sectionCollapsed = store[elem.id] || '0';
            }
        );
    };


    // add the icons on Init
    savvyPanel.add_action( 'savvyInit' , control.sectionIcons.bind( savvyPanel ) , 10 );

    // if storeCollapsedStatus is 1 then add the handleRestoreCollapsedStatus action
    if ( storeCollapsedStatus === '1') {
        savvyPanel.add_action( 'savvyInit' , control.handleRestoreCollapsedStatus.bind( savvyPanel ) , 5 );
        savvyPanel.add_action( 'savvyUpdate' , control.handleStoreCollapsedStatus.bind( savvyPanel ) , 5 );
    }    

}) ( window.savvyPanel );