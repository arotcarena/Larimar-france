import React from 'react';
import { CloseIcon } from '../Icon/CloseIcon';
import { Button } from './Button';

export const CloseButton = ({onClick, additionalClass, lang='fr', ...props}) => {
    const ariaLabel = lang === 'en' ? 'Close': 'Fermer';
    return (
        <Button additionalClass={`icon-button ${additionalClass}`} aria-label={ariaLabel} onClick={onClick} {...props}> 
            <CloseIcon />
        </Button>
    )
}