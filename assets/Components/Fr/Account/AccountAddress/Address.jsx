import React, { useState } from 'react';
import { EditButton } from '../../../../UI/Button/EditButton';
import { CloseButton } from '../../../../UI/Button/CloseButton';
import { useOpenState } from '../../../../CustomHook/useOpenState';
import { AddressUpdate } from './AddressUpdate';
import { Loader } from '../../../../UI/Icon/Loader';

export const Address = ({address, update, doDelete, loading, onServerError}) => {


    const [updateFormIsOpen, openUpdateForm, closeUpdateForm] = useOpenState(false);

    const handleUpdate = () => {
        if(loading || isRemoving) {
            return;
        }
        openUpdateForm();
    };
    

    //delete
    const [removeConfirmationIsOpen, openRemoveConfirmation, closeRemoveConfirmation] = useOpenState(false);
    const [isRemoving, setRemoving] = useState(false);
    const handleDeleteClick = () => {
        if(loading) {
            return;
        }
        openRemoveConfirmation();
    };
    const confirmDelete = async e => {
        setRemoving(true);
        await doDelete(address.id);
        setRemoving(false);
        closeRemoveConfirmation();
    };

    if(updateFormIsOpen) {
        return (
            <li className="address-item active">
                <AddressUpdate address={address} update={update} close={closeUpdateForm} onServerError={onServerError} />
            </li>
        )
    }

    return (
        <li className={'address-item' + (removeConfirmationIsOpen ? ' danger': '') + (isRemoving ? ' removing': '')}>
            <div className="capitalize">{address.civility} {address.firstName} {address.lastName}</div>
            <div>{address.lineOne}</div>
            <div>{address.lineTwo}</div>
            <div>{address.postcode} {address.city}</div>
            <div>{address.country}</div>
            {
                !removeConfirmationIsOpen && (
                    <div className="address-item-controls">
                        <CloseButton onClick={handleDeleteClick} additionalClass="address-item-button" disabled={loading} />
                        <EditButton onClick={handleUpdate} additionalClass="address-item-button" disabled={loading} />
                    </div>
                )
            }
            {
                removeConfirmationIsOpen && (
                    <div className="address-item-sub-controls">
                        <span>Supprimer ?</span>
                        <div>
                            <button className="address-item-sub-button yes" onClick={confirmDelete}>Oui</button>
                            <button className="address-item-sub-button no" onClick={closeRemoveConfirmation}>Non</button>
                        </div>
                    </div>
                )
            }
            {
                isRemoving && <Loader additionalClass="main-loader" />
            }
        </li>
    )
}