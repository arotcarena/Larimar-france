import React, { useEffect, useState } from 'react';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { AccountButton } from '../../../../../UI/Button/AccountButton';
import { AccountCard } from './AccountCard';
import '../../../../../styles/header/HeaderTop/accountCard.css';
import { useControlledFetch } from '../../../../../CustomHook/fetch/useControlledFetch';
import { apiFetch } from '../../../../../functions/api';

export const AccountControl = () => {

    const [isOpen, open, close] = useOpenState(false);

    const handleClick = e => {
        e.stopPropagation();
        if(isOpen) {
            e.preventDefault();
            close();
        } else {
            e.preventDefault();
            open();
        }
    }

    const [user, setUser] = useState(null);

    useEffect(() => {
        if(user === null) {
            (async () => {
                try {
                    const user = await apiFetch('/fr/api/user/getCivilState');
                    setUser(user);
                } catch(e) {
                    setUser(false);
                }
            })();
        }
    }, []);

    return (
        <AccountButton additionalClass="account-opener" logged={(user !== null) && (user !== false)} lang="en" onMouseOver={open} onMouseLeave={close} onClick={handleClick}>
            {
                isOpen && <AccountCard close={close} user={user} />
            }
        </AccountButton>
    )
}