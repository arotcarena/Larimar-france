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
    /** @type {HTMLElement} */
    #current;

    /** @type {HTMLElement} */
    #next;

    /** @type {HTMLElement} */
    #prev;

    // #afterChangeBinded;

    connect() {
        this.#current = this.element.querySelector('.hero-bg-1'),
        this.#next = this.element.querySelector('.hero-bg-2'),
        this.#prev = this.element.querySelector('.hero-bg-3')

        // this.#afterChangeBinded = this.#afterChange.bind(this);

        setInterval(() => {
            this.#change();
        }, 6000);
    }

    // #change() {
    //     this.#current.classList.add('disappears');
    //     this.#next.classList.add('appears');

    //     this.#next.addEventListener('animationend', this.#afterChangeBinded);
    // }

    // #afterChange() {
    //     this.#next.removeEventListener('animationend', this.#afterChangeBinded);
        
    //     this.#next.classList.remove('appears');
    //     this.#current.classList.remove('disappears');

    //     this.#handleNext();
    // }

    #change() {
        const prev = this.#prev;
        const current = this.#current;
        const next = this.#next;

        current.classList.remove('current');
        next.classList.add('current');
        this.element.classList.remove('current-'+current.dataset.id);
        this.element.classList.add('current-'+next.dataset.id);

        this.#current = next;
        this.#next = prev;
        this.#prev = current;

    }

}
