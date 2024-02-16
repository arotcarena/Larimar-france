import React from 'react';
import { Button } from './Button';
import { AccountIcon } from '../Icon/AccountIcon';
import { AccountLoggedIcon } from '../Icon/AccountLoggedIcon';

export const AccountButton = ({children, onClick, additionalClass, lang='fr', logged = false, ...props}) => {
    const ariaLabel = lang === 'en' ? 'My Account': 'Mon Compte';
    return (
        <button className={`button button-link icon-button ${additionalClass}`} aria-label={ariaLabel} onClick={onClick} {...props}> 
            {
                logged ? <AccountLoggedIcon />: <AccountIcon />
            }
            {children}
        </button>
    )
}











