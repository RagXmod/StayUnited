// Import bootstrap dependencies
import './bootstrap';
import Template from './lib/template';


// App extends Template
export default class App extends Template {
    /*
     * Auto called when creating a new instance
     *
     */
    constructor() {
        super();
    }

     _uiInit() {
         // Call original function
         super._uiInit();

         // Your extra JS code afterwards
        var token = document.head.querySelector('meta[name="csrf-token"]');

        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }
}

// Once everything is loaded
jQuery(() => {

    // Create a new instance of App
   window.dcm = new App();

});
