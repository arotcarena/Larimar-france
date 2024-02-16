import React from 'react';
import { AccountNavLink } from './AccountNavLink';

export const AccountDesktopMenu = () => {

    return (
        <nav className="account-desktop-menu">
            <AccountNavLink to="/details">Details</AccountNavLink>
            <AccountNavLink to="/orders">Orders</AccountNavLink>
            <AccountNavLink to="/addresses">Addresses</AccountNavLink>
        </nav>
    )
}

