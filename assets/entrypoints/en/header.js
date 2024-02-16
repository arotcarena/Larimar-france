
import React from 'react';
import { createRoot } from 'react-dom/client';
import { Header } from '../../Components/En/Header';
import { scrollListener } from '../../functions/scrollListener';



const header = document.getElementById('header');
const headerRoot = createRoot(header);

headerRoot.render(
    <Header 
        activeCategory={header.dataset.activecategory} 
        activeSubCategory={header.dataset.activesubcategory}
        />
);



scrollListener(
    () => {
        header.classList.remove('scroll');
        if(window.scrollY > 75) {
            header.classList.add('scroll-up');
        } else {
            header.classList.add('fade');
            const animationListener = e => {
                header.classList.remove('scroll-up');
                header.classList.remove('from-up');
                header.removeEventListener('animationend', animationListener);
            }
            header.addEventListener('animationend', animationListener);
        }
    },
    () => {
        if(header.classList.contains('scroll-up')) {
            header.classList.remove('scroll-up');
            header.classList.add('from-up');
        }
            if(window.scrollY > 75) {
                header.classList.add('scroll');
                header.classList.remove('fade');
            }
    },
    70
);




