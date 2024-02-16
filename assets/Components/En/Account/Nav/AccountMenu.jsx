import React from 'react';
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
                <AccountNavLink to="/details" onClick={close}>Details</AccountNavLink>
                <AccountNavLink to="/orders" onClick={close}>Orders</AccountNavLink>
                <AccountNavLink to="/addresses" onClick={close}>Addresses</AccountNavLink>
                <button className="account-menu-opener" onClick={close}>
                    <ExpandMoreIcon additionalClass="expanded" />
                </button>
            </nav>
        )
    }

    return (
        <nav className="account-menu close" onClick={expand}>
            {
                pathname === '/details' && (
                    <button className="account-menu-button active">
                        Details
                    </button>
                )
            }
            {
                pathname === '/orders' && (
                    <button className="account-menu-button active">
                        Orders
                    </button>
                )
            }
            {
                pathname === '/addresses' && (
                    <button className="account-menu-button active">
                        Addresses
                    </button>
                )
            }
            <button className="account-menu-opener" onClick={expand}>
                <ExpandMoreIcon />
            </button>
        </nav>
    )
}