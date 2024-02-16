import React, { useState } from 'react';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { Modal } from '../../../../../UI/Container/Modal';
import '../../../../../styles/Shop/ProductShow/fingerSizeForm.css';
import { FingerSizeForm } from './FingerSizeForm';

export const FingerSizeChoice = ({publicRef}) => {
    
    const [formIsOpen, openForm, closeForm] = useOpenState(false);

    const handleClick = e => {
        e.preventDefault();
        setSuccess(false);
        openForm();
    }

    
    const [success, setSuccess] = useState(false);
    
    const handleSuccess = () => {
        closeForm();
        setSuccess(true);
    }

    return (
        <>
            <button className="fingerSize-control" onClick={handleClick}>Demander ma taille</button>
            <Modal additionalClass="fingerSize-form-modal" isOpen={formIsOpen} close={closeForm}>
                <FingerSizeForm publicRef={publicRef} onSuccess={handleSuccess} />
            </Modal>
            {
                success && <div className="fingerSize-success">Votre demande a bien été envoyée !</div>
            }
        </>

    )
}