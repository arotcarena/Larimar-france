import React from 'react';

export const LeftDoubleArrowIcon = ({additionalClass, lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'left double arrow': 'flèche gauche double'; 

    return (
        <svg className={'icon i-left-double-arrow' + (additionalClass ? ' '+additionalClass: '')} aria-label={ariaLabel} {...props} xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path fill="currentColor"d="M453-241 213-481l240-240 42 42-198 198 198 198-42 42Zm253 0L466-481l240-240 42 42-198 198 198 198-42 42Z"/>
        </svg>
    )
}