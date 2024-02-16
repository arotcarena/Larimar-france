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

    #onMoreClick;

    #onLessClick

    connect() {
        this.#onMoreClick = this.#handleMoreClick.bind(this);
        this.#onLessClick = this.#handleLessClick.bind(this);

        this.element.querySelector('.content-b-button').addEventListener('click', this.#onMoreClick);
    }

    #handleMoreClick(e) {
        e.preventDefault();
        this.element.querySelector('.content-a').classList.add('invisible');
        this.element.querySelector('.content-b').classList.remove('invisible');

        this.element.querySelector('.content-b-button').removeEventListener('click', this.#onMoreClick);
        this.element.querySelector('.content-a-button').addEventListener('click', this.#onLessClick);
    }

    #handleLessClick(e) {
        e.preventDefault();
        this.element.querySelector('.content-b').classList.add('invisible');
        this.element.querySelector('.content-a').classList.remove('invisible');

        this.element.querySelector('.content-a-button').removeEventListener('click', this.#onLessClick);
        this.element.querySelector('.content-b-button').addEventListener('click', this.#onMoreClick);
    }
}
