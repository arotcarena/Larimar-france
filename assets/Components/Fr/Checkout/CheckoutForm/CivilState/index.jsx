import React from 'react';
import { EditButton } from '../../../../../UI/Button/EditButton';
import { CivilStateForm } from './CivilStateForm';
import { STEP_CIVIL_STATE } from '../../../../En/Checkout/CheckoutForm';







export const CivilState = ({edit, civilState, setCheckoutData, selectStep, forwardStep}) => {

    const handleEdit = () => {
        selectStep(STEP_CIVIL_STATE);
    }


    return (
        <div className={'form-block' + (edit ? '': ' editable')}>
            <h3 className="form-block-title">Mes informations</h3>
            {
                edit 
                ?
                <CivilStateForm civilState={civilState} setCheckoutData={setCheckoutData} forwardStep={forwardStep} />
                :
                (
                    <>
                        <div className="info-group">
                            <p className="capitalize">{civilState.civility} {civilState.firstName} {civilState.lastName}</p>
                            <p>{civilState.email}</p>
                        </div>
                        <EditButton onClick={handleEdit} />
                    </>
                )
            }
        </div>
    )
}





