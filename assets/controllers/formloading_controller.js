import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {

    connect() {
        const form = this.element.querySelector('form');
        const button = form.querySelector('button[type=submit]');
        form.addEventListener('submit', e => {
            if(button.classList.contains('loading')) {
                e.preventDefault();
                return;
            }
            button.classList.add('loading');
            button.setAttribute('disabled', '');
        });
    }

}
