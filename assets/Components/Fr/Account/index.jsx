import React, { useState } from 'react';
import { AccountProfile } from './AccountProfile';
import { AccountPurchase } from './AccountPurchase';
import { AccountAddress } from './AccountAddress';
import { useControlledFetch } from '../../../CustomHook/fetch/useControlledFetch';
import '../../../styles/Account/index.css';
import { AccountMenu } from './Nav/AccountMenu';
import { AccountDesktopMenu } from './Nav/AccountDesktopMenu';
import { useControlledCRUD } from '../../../CustomHook/useControlledCRUD';
import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { LogoutIcon } from '../../../UI/Icon/LogoutIcon';


// menu
export const ACCOUNT_PROFILE  = 'account_profile';
export const ACCOUNT_PURCHASE = 'account_purchase';
export const ACCOUNT_ADDRESS = 'account_address';



export const Account = ({dbUser}) => {


    //profile
    const [user, setUser] = useState(dbUser);
    //address
    const addressCrud = useControlledCRUD('/fr/api/address');
    //purchase
    const purchaseManager = useControlledFetch();


    return (
            <div className="account">
                <h1>Mon compte</h1>
                <BrowserRouter basename="/fr/mon-compte">
                    <AccountMenu />
                    <AccountDesktopMenu />
                    <div className="account-body">
                        <Routes>
                            <Route path="/" element={<Navigate to="/mes-informations"/>} />

                            <Route path="/mes-informations" element={<AccountProfile user={user} setUser={setUser} />} />
                            
                            <Route path="/mes-commandes" element={<AccountPurchase manager={purchaseManager} />} />
                            
                            <Route path="/mes-adresses" element={<AccountAddress addressCrud={addressCrud} />} />
                        </Routes>
                    </div>
                </BrowserRouter>
                <div className="account-footer">
                    <a className="account-link logout-link" href="/logout">
                        <LogoutIcon />
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
    )
}