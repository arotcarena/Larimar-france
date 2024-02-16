import React from 'react';
import { Button } from './Button';
import { CartIcon } from '../Icon/CartIcon';

export const CartButton = ({children, onClick, additionalClass, lang='fr', ...props}) => {
    const ariaLabel = lang === 'en' ? 'Cart': 'Panier';
    return (
        <Button additionalClass={`icon-button ${additionalClass}`} aria-label={ariaLabel} onClick={onClick} {...props}> 
            <CartIcon />
            {children}
        </Button>
    )
}