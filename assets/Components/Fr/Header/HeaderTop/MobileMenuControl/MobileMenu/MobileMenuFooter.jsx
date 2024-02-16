import React from 'react';

export const MobileMenuFooter = () => {
    return (
        <div className="mobile-menu-footer">
            <a className="footer-link" href="/fr/qui-suis-je">Qui suis-je ?</a>
            <a className="footer-link" href="mailto:contact@larimar-france.com">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path stroke="currentColor" fill="currentColor" d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm340-302 340-223v-55L480-522 140-740v55l340 223Z"/>
                </svg>
                <span>contact@larimar-france.com</span>
            </a>
            <a className="footer-link" href="tel:+33613308707">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
                    <path stroke="currentColor" fill="currentColor" d="M795-120q-122 0-242.5-60T336-336q-96-96-156-216.5T120-795q0-19 13-32t32-13h140q14 0 24.5 9.5T343-805l27 126q2 14-.5 25.5T359-634L259-533q56 93 125.5 162T542-254l95-98q10-11 23-15.5t26-1.5l119 26q15 3 25 15t10 28v135q0 19-13 32t-32 13Z"/>
                </svg>
                <span>+33 6 13 30 87 07</span>
            </a>
            <div className="mobile-menu-footer-social">
                <a href="https://www.facebook.com/groups/715250349390115">
                    <img class="social-icon" src="/img/icons/social/facebook.png" alt="logo facebook" />
                </a>
                <a href="https://www.instagram.com/larimarfrance/">
                    <img class="social-icon" src="/img/icons/social/instagram.png" alt="logo instagram" />
                </a>
                <a href="https://www.youtube.com/@radiomineraux5553">
                    <img class="social-icon" src="/img/icons/social/youtube.png" alt="logo youtube" />
                </a>
            </div>
            <div className="lang-choices">
                <a className="lang-choice selected" href="/">
                    <img class="flag-icon" src="/img/icons/lang/fr.png" alt="drapeau français" />
                </a>
                <span>/</span>
                <a className="lang-choice" href="/en">
                    <img className="flag-icon" src="/img/icons/lang/en.png" alt="drapeau du royaume-uni" />
                </a>
            </div>
        </div>
    )
}