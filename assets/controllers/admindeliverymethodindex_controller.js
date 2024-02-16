import { Controller } from '@hotwired/stimulus';


export default class extends Controller {

    #menuButtons;

    #showGroups;

    connect() {
        this.#menuButtons = this.element.querySelectorAll('.admin-sub-menu-control');
        this.#showGroups = this.element.querySelectorAll('.deliveryMethod-group');

        this.#showGroups.forEach(showGroup => showGroup.classList.add('invisible'));
        this.#menuButtons.forEach(menuButton => menuButton.addEventListener('click', this.#onMenuButtonClick.bind(this)));

        //affichage par défaut
        this.element.querySelector('#trackedLetter').classList.remove('invisible');
        this.element.querySelector('')
    }

    #onMenuButtonClick(e) {
        const button = e.target;
        this.#menuButtons.forEach(button => {
            if(button.classList.contains('active')) {
                button.classList.remove('active');
            }
        })
        button.classList.add('active');

        this.#showGroups.forEach(showGroup => {
            if(!showGroup.classList.contains('invisible')) {
                showGroup.classList.add('invisible');
            }
        })
        this.element.querySelector('#'+button.dataset.key).classList.remove('invisible');
    }

}
