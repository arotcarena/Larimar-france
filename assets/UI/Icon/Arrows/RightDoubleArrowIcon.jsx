import React from 'react';

export const RightDoubleArrowIcon = ({additionalClass, lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'right double arrow': 'flèche droite double'; 

    return (
        <svg className={'icon i-right-double-arrow' + (additionalClass ? ' '+additionalClass: '')} aria-label={ariaLabel} {...props} xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path fill="currentColor" d="m255-241-42-42 198-198-198-198 42-42 240 240-240 240Zm253 0-42-42 198-198-198-198 42-42 240 240-240 240Z"/>
        </svg>
    )
}
