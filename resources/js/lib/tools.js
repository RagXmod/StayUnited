
// Import global dependencies
import './../bootstrap';

// Tools
export default class Tools {

    /*
     * Returns current browser's window width
     *
     */
    static getWidth() {
        return window.innerWidth
            || document.documentElement.clientWidth
            || document.body.clientWidth;
    }
}