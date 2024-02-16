import React, { useState } from 'react';
import { STEP_DELIVERY_ADDRESS } from '..';
import { DeliveryAddressUpdate } from './DeliveryAddressUpdate.';
import { useCRUD } from '../../../../../CustomHook/useCRUD';
import { DeliveryAddressCreate } from './DeliveryAddressCreate';
import { AddressItem } from './AddressItem';
import { Loader } from '../../../../../UI/Icon/Loader';
import { EditButton } from '../../../../../UI/Button/EditButton';
import { AddButton } from '../../../../../UI/Button/AddButton';
import { translateCivility } from '../../../../../functions/translaters';
import { ApiError } from '../../../../../functions/api';
import { STEP_DELIVERY_METHOD } from '../../../../Fr/Checkout/CheckoutForm';


export const DeliveryAddress = ({edit, deliveryAddress, setCheckoutData, selectStep}) => {

    const handleEdit = () => {
        selectStep(STEP_DELIVERY_ADDRESS);
    }
   
    if(!edit) {
        return (
            <div className={'form-block' + (edit ? '': ' editable')}>
                <h3 className="form-block-title">Delivery address</h3>
                <div className="info-group">
                    <p className="capitalize">{translateCivility(deliveryAddress.civility)} {deliveryAddress.firstName} {deliveryAddress.lastName}</p>
                    <p>{deliveryAddress.lineOne}</p>
                    <p>{deliveryAddress.lineTwo}</p>
                    <p>{deliveryAddress.postcode} {deliveryAddress.city}</p>
                    <p>{deliveryAddress.enCountry}</p>
                </div>
                <EditButton onClick={handleEdit} />
            </div>
        )
    }

    const [addresses, updateAddress, createAddress, deleteAddress, loading, errors] = useCRUD('/en/api/address');
    const [state, setState] = useState({
        target: null,
        action: null
    });
    
    const handleSubmit = (formData) => {
        setCheckoutData(checkoutData => ({
            ...checkoutData,
            deliveryAddress: formData,
            deliveryMethod: null
        }));
        selectStep(STEP_DELIVERY_METHOD);
    }

    const handleSelect = address => {
        handleSubmit(address);
    }

    const handleUpdate = address => {
        setState({
            action: 'update',
            target: address
        });
    };
    const handleCreate = e => {
        e.preventDefault();
        setState({
            action: 'create',
            target: null
        });
    };
    const handleCancel = e => {
        e.preventDefault();
        setState({
            action: null,
            target: null
        });
    };


    //server error
    const [serverErrors, setServerErrors] = useState(null);
    const handleServerError = e => {
        if(e instanceof ApiError) {
            setServerErrors(e.errors);
        } else {
            setServerErrors(['Action impossible pour le moment. Veuillez réessayer ultérieurement']);
        }
        setState({
            target: null,
            action: null
        });
        setTimeout(() => {
            setServerErrors(null);
        }, 3000);
    }

    return (
        <div className="form-block">
        <h3 className="form-block-title">Delivery address</h3>
        {
            serverErrors && <div className="js-flash error">{serverErrors[0]}</div>
        }
        {
            state.action === null && (
                <div>
                {
                    loading && (
                        <div className="info-group no-ml">
                            <Loader />
                        </div>
                    )
                }
                {
                    addresses.length > 0 
                    ?
                    <>
                        <div className="info-group">Choose :</div>
                        <ul>
                        {
                            addresses.map(address => {
                                return (
                                    <AddressItem 
                                        key={address.id}
                                        address={address} 
                                        onSelect={handleSelect} 
                                        onUpdate={handleUpdate} 
                                        onDelete={deleteAddress} 
                                        loading={loading}
                                    />
                                )
                            })
                        }
                        </ul>
                    </>
                    :
                    !loading && <div className="info-group">Add an address</div>
                    
                }
                    <div className="add-button-wrapper">
                        <AddButton onClick={handleCreate} />
                    </div>
                </div>
            )
        }

        {
            state.action === 'create' && (
                <DeliveryAddressCreate mainSubmit={handleSubmit} create={createAddress} onCancel={handleCancel} onServerError={handleServerError} />
            )
        }

        {
            state.action === 'update' && (
                <DeliveryAddressUpdate address={state.target} mainSubmit={handleSubmit} update={updateAddress} onCancel={handleCancel} onServerError={handleServerError} />
            )
        }
            
        </div>
        
    )
}


