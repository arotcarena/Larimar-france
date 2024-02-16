import React, { useState } from 'react';
import { useOpenState } from '../../../../CustomHook/useOpenState';
import { ExpandMoreIcon } from '../../../../UI/Icon/ExpandMoreIcon';
import { useLocation } from 'react-router-dom';
import { AccountNavLink } from './AccountNavLink';



export const AccountMenu = () => {

    const [isExpanded, expand, close] = useOpenState(false);

    const { pathname } = useLocation();
    

    if(isExpanded) {
        return (
            <nav className="account-menu">
                <AccountNavLink to="/mes-informations" onClick={close}>Mes informations</AccountNavLink>
                <AccountNavLink to="/mes-commandes" onClick={close}>Mes commandes</AccountNavLink>
                <AccountNavLink to="/mes-adresses" onClick={close}>Mes adresses</AccountNavLink>
                <button className="account-menu-opener" onClick={close}>
                    <ExpandMoreIcon additionalClass="expanded" />
                </button>
            </nav>
        )
    }

    return (
        <nav className="account-menu close" onClick={expand}>
            {
                pathname === '/mes-informations' && (
                    <button className="account-menu-button active">
                        Mes informations
                    </button>
                )
            }
            {
                pathname === '/mes-commandes' && (
                    <button className="account-menu-button active">
                        Mes commandes
                    </button>
                )
            }
            {
                pathname === '/mes-adresses' && (
                    <button className="account-menu-button active">
                        Mes adresses
                    </button>
                )
            }
            <button className="account-menu-opener" onClick={expand}>
                <ExpandMoreIcon />
            </button>
        </nav>
    )
}