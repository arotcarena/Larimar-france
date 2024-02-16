import { Controller } from '@hotwired/stimulus';



export default class extends Controller {

    #onClick;

    /** @type {HTMLElement} */
    #toggleElt;
    
    /** @type {HTMLElement} */
    #button;

    /** @type {boolean} */
    #isOpen = false;

    connect() {
        this.#onClick = this.#handleClick.bind(this);

        this.#toggleElt = this.element.querySelector('.toggle');
        this.#button = this.element.querySelector('.toggle-opener');

        this.#button.addEventListener('click', this.#onClick);
    }

    #handleClick(e) {
        e.preventDefault();
        if(this.#isOpen) {
            this.#close();
        } else {
            this.#open();
        }
    }

    #open() {
        this.#toggleElt.classList.remove('invisible');
        this.#button.classList.add('expanded');
        this.#isOpen = true;
    }

    #close() {
        this.#toggleElt.classList.add('invisible');
        this.#button.classList.remove('expanded');
        this.#isOpen = false;
    }

   
}
