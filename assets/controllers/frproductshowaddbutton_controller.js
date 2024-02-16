import { Controller } from '@hotwired/stimulus';
import { ApiError, apiFetch } from '../functions/api';
import { cartChipAdd } from '../functions/dom';

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

    #errorTimeout = 2000;

    #isLoading = false;

    connect() {
        this.element.addEventListener('click', this.#handleClick.bind(this));
    }

    async #handleClick(e) {
        if(this.#isLoading) {
            return;
        }
        e.preventDefault();
        try {
            this.#setLoading();
            await apiFetch('/fr/api/cart/add/id-'+this.element.dataset.productid+'_quantity-'+this.element.dataset.quantitytoadd);
            cartChipAdd(parseInt(this.element.dataset.quantitytoadd), parseInt(this.element.dataset.productprice));
        } catch(e) {
            const error = document.createElement('div');
            error.classList.add('form-error');
            if(e instanceof ApiError) {
                error.innerText = e.errors;
            } else {
                throw e;
            }
            this.element.parentElement.append(error);
            setTimeout(() => {
                error.remove();
            }, this.#errorTimeout);
            this.#removeLoading();
            throw e;
        }
        this.#removeLoading();
    }   

    #setLoading() {
        this.element.querySelector('.loader').classList.remove('invisible');
        this.element.querySelector('span').classList.add('invisible');
        this.element.classList.add('disabled');
        this.element.setAttribute('disabled', '');
        this.#isLoading = true;
    }

    #removeLoading() {
        this.element.querySelector('.loader').classList.add('invisible');
        this.element.querySelector('span').classList.remove('invisible');
        this.element.classList.remove('disabled');
        this.element.removeAttribute('disabled');
        this.#isLoading = false;
    }
}
