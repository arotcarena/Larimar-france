import React from 'react';
import { NavLink } from "react-router-dom";

export const AccountNavLink = ({children, to, ...props}) => {
    return (
        <NavLink 
            to={to} 
            className={({ isActive, isPending }) => isPending ? "account-menu-button pending" : isActive ? "account-menu-button active" : "account-menu-button"}
            {...props}
        >
            {children}
        </NavLink>
    )
}