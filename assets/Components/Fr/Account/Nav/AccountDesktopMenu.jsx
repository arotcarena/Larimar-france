import React from 'react';
import { AccountNavLink } from './AccountNavLink';

export const AccountDesktopMenu = () => {

    return (
        <nav className="account-desktop-menu">
            <AccountNavLink to="/mes-informations">Mes informations</AccountNavLink>
            <AccountNavLink to="/mes-commandes">Mes commandes</AccountNavLink>
            <AccountNavLink to="/mes-adresses">Mes adresses</AccountNavLink>
        </nav>
    )
}

