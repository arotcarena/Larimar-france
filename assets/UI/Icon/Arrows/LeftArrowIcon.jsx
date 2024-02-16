import React from 'react';

export const LeftArrowIcon = ({additionalClass, lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'left arrow': 'flèche gauche'; 

    return (
        <svg className={'icon i-left-arrow' + (additionalClass ? ' '+additionalClass: '')} aria-label={ariaLabel} {...props} xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path fill="currentColor" d="M561-240 320-481l241-241 43 43-198 198 198 198-43 43Z"/>
        </svg>
    )
}