import React from 'react';
import { STEP_INVOICE_ADDRESS } from '..';
import { EditButton } from '../../../../../UI/Button/EditButton';
import { InvoiceAddressForm } from './InvoiceAddressForm';



export const InvoiceAddress = ({edit, invoiceAddress, defaultAddress, civilState, setCheckoutData, selectStep, forwardStep}) => {

    const handleEdit = () => {
        selectStep(STEP_INVOICE_ADDRESS);
    }

    

    return (
        <div className={'form-block' + (edit ? '': ' editable')}>
            <h3 className="form-block-title">Adresse de facturation</h3>
            <div>
                <div className="info-group no-mb capitalize">
                    <p>{civilState.civility} {civilState.firstName} {civilState.lastName}</p>
                </div>
            </div>
            {
                edit
                ?
                <InvoiceAddressForm 
                    invoiceAddress={invoiceAddress} 
                    defaultAddress={defaultAddress} 
                    civilState={civilState} 
                    setCheckoutData={setCheckoutData} 
                    forwardStep={forwardStep} 
                />
                :
                (
                    <>
                        <div className="info-group no-mt">
                            <p>{invoiceAddress.lineOne}</p>
                            <p>{invoiceAddress.lineTwo}</p>
                            <p>{invoiceAddress.postcode}</p>
                            <p>{invoiceAddress.city}</p>
                            <p>{invoiceAddress.country}</p>
                        </div>
                        <EditButton onClick={handleEdit} />
                    </>
                )
            }
        </div>
        
    )
}