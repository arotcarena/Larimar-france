import React from 'react';
import { MenuIcon } from '../Icon/MenuIcon';
import { Button } from './Button';

export const MenuButton = ({onClick, additionalClass, lang='fr', ...props}) => {
    const ariaLabel = lang === 'en' ? 'Menu': 'Menu';
    return (
        <Button additionalClass={`icon-button ${additionalClass}`} aria-label={ariaLabel} onClick={onClick} {...props}> 
            <MenuIcon />
        </Button>
    )
}