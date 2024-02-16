import React from 'react';

export const RightArrowIcon = ({additionalClass, lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'right arrow': 'flèche droite'; 

    return (
        <svg className={'icon i-right-arrow' + (additionalClass ? ' '+additionalClass: '')} aria-label={ariaLabel} {...props} xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path fill="currentColor" d="m375-240-43-43 198-198-198-198 43-43 241 241-241 241Z"/>
        </svg>
    )
}
