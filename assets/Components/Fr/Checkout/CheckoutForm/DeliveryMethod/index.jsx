import React, { useState } from 'react';
import { DeliveryMethodForm } from './DeliveryMethodForm';
import { EditButton } from '../../../../../UI/Button/EditButton';
import { STEP_DELIVERY_METHOD } from '..';
import { priceFormater } from '../../../../../functions/formaters';
import { RelayChoiceForm } from './RelayChoiceForm';

export const DeliveryMethod = ({edit, selectStep, forwardStep, deliveryAddress, cart, deliveryMethod, customsFeesAlert, setCheckoutData}) => {

    const handleEdit = () => {
        selectStep(STEP_DELIVERY_METHOD);
    }

    const [choices, setChoices] = useState(null);

    const [isChoosingRelay, setChoosingRelay] = useState(false);

    if(isChoosingRelay && deliveryMethod) {
        return (
            <DeliveryMethodBlock edit={edit} customsFeesAlert={customsFeesAlert}>
                <DeliveryMethodInfo deliveryMethod={deliveryMethod} />
                <RelayChoiceForm 
                    deliveryAddress={deliveryAddress} 
                    cart={cart} 
                    setCheckoutData={setCheckoutData} 
                    setChoosingRelay={setChoosingRelay} 
                    forwardStep={forwardStep} 
                />
            </DeliveryMethodBlock>
        )
    }

    if(edit) {
            return (
                <DeliveryMethodBlock edit={true} customsFeesAlert={customsFeesAlert}>
                    <DeliveryMethodForm 
                        choices={choices} 
                        setChoices={setChoices} 
                        deliveryAddress={deliveryAddress} 
                        cart={cart} 
                        deliveryMethod={deliveryMethod} 
                        setCheckoutData={setCheckoutData} 
                        forwardStep={forwardStep} 
                        setChoosingRelay={setChoosingRelay}
                    />
                </DeliveryMethodBlock>
            )
    }

    return (
        <DeliveryMethodBlock edit={edit} customsFeesAlert={customsFeesAlert}>
            {
                !deliveryMethod && <div className="info-group">Choisissez une méthode</div>
            }
            {
                deliveryMethod && <DeliveryMethodInfo deliveryMethod={deliveryMethod} />
            }
            <EditButton onClick={handleEdit} />
        </DeliveryMethodBlock>
    )
}

const DeliveryMethodInfo = ({deliveryMethod}) => {
    return (
        <div className="info-group">
            <p className="info-row">
                <span>{deliveryMethod.name}</span>
                <span>{priceFormater(deliveryMethod.price)}</span> 
                {
                    deliveryMethod.deliveryTime && (
                        <span className="text-small">
                            ({(deliveryTimeLabel(deliveryMethod.deliveryTime))})
                        </span>
                    )
                }
            </p>
            {
                deliveryMethod.relay && (
                    <div className="info-group small">
                        <p>{deliveryMethod.relay.name}</p>
                        <p>{deliveryMethod.relay.lineOne}</p>
                        <p>{deliveryMethod.relay.lineTwo}</p>
                        <p>{deliveryMethod.relay.postcode} {deliveryMethod.relay.city}</p>
                    </div>
                )
            }
        </div>
    )
}

const DeliveryMethodBlock = ({children, edit, customsFeesAlert}) => {
    return (
        <div className={'form-block' + (edit ? '': ' editable')}>
            <h3 className="form-block-title">Méthode de livraison</h3>
            {
                customsFeesAlert && (
                    <div className="form-info uppercase">
                        {customsFeesAlert}
                    </div>
                )
            }
            {children}
        </div>
    )
}


export const deliveryTimeLabel = deliveryTime => {
    return deliveryTime + ' jour' + (deliveryTime > 1 ? 's': '') + ' ouvré' + (deliveryTime > 1 ? 's': '');
}