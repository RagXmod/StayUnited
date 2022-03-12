// Import global dependencies
import './../bootstrap';

import Tools from './tools';
// Import required modules
import Helpers from './helpers';

// Template
export default class Template {
    /*
     * Auto called when creating a new instance
     *
     */
    constructor() {
        this._uiInit();
    }

    /*
     * Init all vital functionality
     *
     */
    _uiInit() {
        this._lPage                 = jQuery('#page-container');
        this._lHeader               = jQuery('#page-header');
        this._headerSearch         = jQuery('#page-header-search');
        this._headerSearchInput    = jQuery('#page-header-search-input');
        this._lHeaderLoader         = jQuery('#page-header-loader');

        // Helper variables
        this._windowW               = Tools.getWidth();


        this._uiApiLayout();
        this._uiHandleNav();


        // Core Helpers Init
        this.helpers([
            'core-bootstrap-tooltip',
            'core-bootstrap-popover',
            'core-bootstrap-tabs',
            'core-bootstrap-custom-file-input',
            'core-toggle-class',
            'core-scroll-to',
            'core-appear',
            'core-ripple'
        ]);
    }

    /*
     * Init base functionality
     *
     */
    init() {
        this._uiInit();
    }

    /*
     * Run Helpers
     *
     */
    helpers(helpers, options = {}) {
        Helpers.run(helpers, options);
    }


    /*
     * Layout API
     *
     */
    _uiApiLayout(mode = 'init') {
        let self = this;

        // Get current window width
        self._windowW = Tools.getWidth();

        // API with object literals
        let layoutAPI = {
            init: () => {
                // Unbind events in case they are already enabled
                self._lPage.off('click.dcm.layout');
                self._lPage.off('click.dcm.overlay');

                // Call layout API on button click
                self._lPage.on('click.dcm.layout', '[data-toggle="layout"]', e => {
                    let el = jQuery(e.currentTarget);

                    self._uiApiLayout(el.data('action'));

                    el.blur();
                });

                // Prepend Page Overlay div if enabled (used when Side Overlay opens)
                if (self._lPage.hasClass('enable-page-overlay')) {
                    self._lPage.prepend('<div id="page-overlay"></div>');

                    jQuery('#page-overlay').on('click.dcm.overlay', e => {
                        self._uiApiLayout('side_overlay_close');
                    });
                }
            },

            header_search_on: () => {
                self._headerSearch.addClass('show');
                self._headerSearchInput.focus();

                // When ESCAPE key is hit close the search section
                jQuery(document).on('keydown.dcm.header.search', e => {
                    if (e.which === 27) {
                        e.preventDefault();
                        self._uiApiLayout('header_search_off');
                    }
                });
            },
            header_search_off: () => {
                self._headerSearch.removeClass('show');
                self._headerSearchInput.blur();

                // Unbind ESCAPE key
                jQuery(document).off('keydown.dcm.header.search');
            },

        };

        // Call layout API
        if (layoutAPI[mode]) {
            layoutAPI[mode]();
        }
    }


    /*
     * Toggle Submenu functionality
     *
     */
    _uiHandleNav() {
        // Unbind event in case it is already enabled
        this._lPage.off('click.dcm.menu');

        // When a submenu link is clicked
        this._lPage.on('click.dcm.menu', '[data-toggle="submenu"]', e => {
            // Get link
            let link = jQuery(e.currentTarget);

            // Check if we are in horizontal navigation, large screen and hover is enabled
            if (!(Tools.getWidth() > 991 && link.parents('.nav-main').hasClass('nav-main-horizontal nav-main-hover'))) {
                // Get link's parent
                let parentLi = link.parent('li');

                if (parentLi.hasClass('open')) {
                    // If submenu is open, close it..
                    parentLi.removeClass('open');
                    link.attr('aria-expanded', 'false');
                } else {
                    // .. else if submenu is closed, close all other (same level) submenus first before open it
                    link.closest('ul').children('li').removeClass('open');
                    parentLi.addClass('open');
                    link.attr('aria-expanded', 'true');
                }

                // Remove focus from submenu link
                link.blur();
            }

            return false;
        });
    }

}
