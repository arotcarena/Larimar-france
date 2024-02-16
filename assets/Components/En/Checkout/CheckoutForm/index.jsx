import React from 'react';
import { CivilState } from './CivilState';
import { DeliveryAddress } from './DeliveryAddress';
import { InvoiceAddress } from './InvoiceAddress';
import { Payment } from './Payment';
import { Loader } from '../../../../UI/Icon/Loader';
import { DeliveryMethod } from './DeliveryMethod';


export const STEP_CIVIL_STATE = 1;
export const STEP_DELIVERY_ADDRESS = 2;
export const STEP_DELIVERY_METHOD = 3;
export const STEP_INVOICE_ADDRESS = 4;
export const STEP_PAYMENT = 5;



export const CheckoutForm = ({cart, loading, checkoutData, setCheckoutData, step, selectStep, forwardStep}) => {

    if(loading) {
        return (
            <div className="checkout-form">
                <h1>Checkout</h1>
                <div className="info-group no-ml">
                    <Loader />
                </div>
            </div>
        )
    }

    return (
        <div className="checkout-form">
            <h1>Checkout</h1>

            <CivilState 
                edit={step === STEP_CIVIL_STATE} 
                civilState={checkoutData.civilState} 
                setCheckoutData={setCheckoutData} 
                selectStep={selectStep}
                forwardStep={forwardStep}
            />

            {
                step >= 2 && (

                    <DeliveryAddress 
                        edit={step === STEP_DELIVERY_ADDRESS} 
                        deliveryAddress={checkoutData.deliveryAddress} 
                        setCheckoutData={setCheckoutData} 
                        selectStep={selectStep}
                    />
                )
            }

            
            {
                step >= 3 && (

                    <DeliveryMethod 
                        edit={step === STEP_DELIVERY_METHOD}
                        selectStep={selectStep}
                        forwardStep={forwardStep}
                        deliveryAddress={checkoutData.deliveryAddress} 
                        cart={cart}
                        deliveryMethod={checkoutData.deliveryMethod} 
                        setCheckoutData={setCheckoutData} 
                        customsFeesAlert={checkoutData.customsFeesAlert}
                    />
                )
            }

            {
                step >= 4 && (

                    <InvoiceAddress 
                        edit={step === STEP_INVOICE_ADDRESS} 
                        invoiceAddress={checkoutData.invoiceAddress} 
                        defaultAddress={checkoutData.deliveryAddress}
                        civilState={checkoutData.civilState} // car dans invoiceAddress le nom n'est pas modifiable, c'est celui de civilState
                        setCheckoutData={setCheckoutData} 
                        selectStep={selectStep}
                        forwardStep={forwardStep}
                    />
                )
            }


            {
                step >= 5 && (

                    <Payment checkoutData={checkoutData} cart={cart} />
                )
            }

        </div>
    )
}