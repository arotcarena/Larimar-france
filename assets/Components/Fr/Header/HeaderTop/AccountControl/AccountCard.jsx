import React, { useEffect } from 'react';
import { Loader } from '../../../../../UI/Icon/Loader';
import { AccountIcon } from '../../../../../UI/Icon/AccountIcon';
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
                                <p>Vous n'êtes pas identifié</p>
                            </div>
                            <LinkButton href="/fr/connexion">Connexion</LinkButton> 
                            <LinkButton href="/fr/créer-un-compte" additionalClass="secondary">Inscription</LinkButton> 
                        </div>
                    )
                }
                {
                    user && (
                        <>
                            <div className="account-card-body">
                                <AccountLoggedIcon additionalClass="icon-big" lang="fr"/>
                                <div className="account-card-text">
                                    <a className="simple-link" href="/fr/mon-compte">
                                        <p className="capitalize">{user.civility} {user.firstName} {user.lastName}</p>
                                        <p>{user.email}</p>
                                    </a>
                                </div>
                            </div>
                            <div className="account-card-separator"></div>
                            <div className="account-card-controls">
                                <LinkButton href="/fr/mon-compte/mes-informations">Mes informations</LinkButton> 
                                <LinkButton href="/fr/mon-compte/mes-commandes">Mes commandes</LinkButton> 
                                <LinkButton href="/fr/mon-compte/mes-adresses">Mes adresses</LinkButton> 
                            </div>
                            <div className="account-card-separator"></div>
                            <a className="link-mute simple-link" href="/logout">
                                <LogoutIcon />
                                <span>Déconnexion</span>
                            </a>
                        </>
                    )
                }
            </div>
        </div>
    )
}