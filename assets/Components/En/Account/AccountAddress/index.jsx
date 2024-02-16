import React, { useEffect, useState } from 'react';
import { Loader } from '../../../../UI/Icon/Loader';
import { Address } from './Address';
import '../../../../styles/Account/address.css';
import { AddressCreateControl } from './AddressCreateControl';
import { ApiError } from '../../../../functions/api';

export const AccountAddress = ({addressCrud: [fetchData, addresses, updateAddress, createAddress, deleteAddress, isLoading, errors]}) => {

    useEffect(() => {
        //dans addressCrud logique pour éviter de fetch si déjà initialisé
        fetchData();
    }, [])

    //server error
    const [serverErrors, setServerErrors] = useState(null);

    const handleServerError = error => {
        if(error instanceof ApiError) {
            setServerErrors(error.errors);
        } else {
            setServerErrors(['Action not possible at the moment. Please try again later']);
        }
        setTimeout(() => {
            setServerErrors(null);
          }, 3000);
    }

    return (
        <div className="account-address">
            {
                !isLoading && (
                    <div className="address-count">
                        {addresses.length} address{addresses.length > 1 ? 'es': ''} saved
                    </div>
                )
            }
            {
                isLoading && <Loader lang="en" />
            }
            {
               serverErrors && <div className="js-flash error">{serverErrors[0]}</div>
            }
            {
                addresses.length > 0 && (
                    <ul className="address-list">
                        {
                            addresses.map(address => {
                                return (
                                    <Address
                                        key={address.id}
                                        address={address} 
                                        update={updateAddress} 
                                        doDelete={deleteAddress} 
                                        loading={isLoading}
                                        onServerError={handleServerError}
                                    />
                                )
                            })
                        }
                    </ul>
                )
            }
            <AddressCreateControl create={createAddress} isLoading={isLoading} onServerError={handleServerError} />
        </div>
    )

            
            
        
}




