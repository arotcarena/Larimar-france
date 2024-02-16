import React from 'react';
import { useOpenState } from "../../../../CustomHook/useOpenState";
import { AddressCreate } from "./AddressCreate";
import { AddButton } from "../../../../UI/Button/AddButton";

export const AddressCreateControl = ({create, isLoading, onServerError}) => {
    const [formIsOpen, openForm, closeForm] = useOpenState(false);

    const handleServerError = e => {
        closeForm();
        onServerError(e);
    }

    return (
        <div className="address-create-controls">
            {
                !formIsOpen && !isLoading && (
                    <div className="add-button-wrapper">
                        <AddButton onClick={openForm} lang="en" />
                    </div>
                )   
            }
            {
                formIsOpen && (
                    <div className="address-item active">
                        <AddressCreate create={create} close={closeForm} onServerError={handleServerError} />
                    </div>
                )
            }
        </div>
    )
}