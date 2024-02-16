import React, { useEffect } from 'react';
import { Loader } from '../../../../../UI/Icon/Loader';
import { AccountIcon } from '../../../../../UI/Icon/AccountIcon';
import { translateCivility } from '../../../../../functions/translaters';
import { LinkButton } from '../../../../../UI/Button/LinkButton';
import { AccountLoggedIcon } from '../../../../../UI/Icon/AccountLoggedIcon';
import { LogoutIcon } from '../../../../../UI/Icon/LogoutIcon';

export const AccountCard = ({close, user}) => {
    useEffect(() => {
        document.body.addEventListener('click', close);
        return () => document.body.removeEventListener('click', close);
    }, []);

    const handleClick = e => {
        e.stopPropagation();
    }

    return (
        <div className="account-card-wrapper" onClick={handleClick}>
            <div className="account-card">
                {
                    user === null && <Loader additionalClass="main-loader" lang="en" />
                }
                {
                    user === false && (
                        <div className="account-card-body empty">
                            <div className="account-card-text">
                                <p>You're not logged in</p>
                            </div>
                            <LinkButton href="/en/login">Log in</LinkButton> 
                            <LinkButton href="/en/create-my-account" additionalClass="secondary">Register</LinkButton> 
                        </div>
                    )
                }
                {
                    user && (
                        <>
                            <div className="account-card-body">
                                <AccountLoggedIcon additionalClass="icon-big" lang="en"/>
                                <div className="account-card-text">
                                    <a className="simple-link" href="/en/my-account">
                                        <p className="capitalize">{translateCivility(user.civility)} {user.firstName} {user.lastName}</p>
                                        <p>{user.email}</p>
                                    </a>
                                </div>
                            </div>
                            <div className="account-card-separator"></div>
                            <div className="account-card-controls">
                                <LinkButton href="/en/my-account/details">Details</LinkButton> 
                                <LinkButton href="/en/my-account/orders">Orders</LinkButton> 
                                <LinkButton href="/en/my-account/addresses">Addresses</LinkButton> 
                            </div>
                            <div className="account-card-separator"></div>
                            <a className="link-mute simple-link" href="/logout">
                                <LogoutIcon />
                                <span>Log out</span>
                            </a>
                        </>
                    )
                }
            </div>
        </div>
    )
}