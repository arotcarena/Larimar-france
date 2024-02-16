import React, { useState } from 'react';
import { CheckButton } from '../../../../../UI/Button/CheckButton';
import { CloseButton } from '../../../../../UI/Button/CloseButton';
import { EditButton } from '../../../../../UI/Button/EditButton';
import { translateCivility } from '../../../../../functions/translaters';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { Loader } from '../../../../../UI/Icon/Loader';

export const AddressItem = ({address, onSelect, onUpdate, onDelete, loading}) => {


    const handleSelect = async () => {
        if(loading || isRemoving || removeConfirmationIsOpen) {
            return;
        }
        onSelect(address);
    };
    const handleUpdate = () => {
        if(loading) {
            return;
        }
        onUpdate(address);
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
        e.stopPropagation();
        setRemoving(true);
        await onDelete(address.id);
        setRemoving(false);
        closeRemoveConfirmation();
    };
    const cancelDelete = e => {
        closeRemoveConfirmation();
        e.stopPropagation();
    }


    return (
        <li className={'address-item' + (removeConfirmationIsOpen ? ' danger': '') + (isRemoving ? ' removing': '')} onClick={handleSelect}>
            <div className="capitalize">{translateCivility(address.civility)} {address.firstName} {address.lastName}</div>
            <div>{address.lineOne}</div>
            <div>{address.lineTwo}</div>
            <div>{address.postcode} {address.city}</div>
            <div>{address.enCountry}</div>
            {
                !removeConfirmationIsOpen && !isRemoving && (
                    <div className="address-item-controls">
                        <CheckButton onClick={handleSelect} additionalClass="address-item-button" disabled={loading} />
                        <EditButton onClick={handleUpdate} additionalClass="address-item-button" disabled={loading} />
                        <CloseButton onClick={handleDeleteClick} additionalClass="address-item-button" disabled={loading} />
                    </div>
                )
            }
            {
                removeConfirmationIsOpen && !isRemoving && (
                    <div className="address-item-sub-controls">
                        <span>Remove ?</span>
                        <div>
                            <button className="address-item-sub-button yes" onClick={confirmDelete}>Yes</button>
                            <button className="address-item-sub-button no" onClick={cancelDelete}>No</button>
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