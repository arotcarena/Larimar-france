import React, { useEffect, useState } from 'react';
import { CartSummary } from './CartSummary';
import { CheckoutForm, STEP_CIVIL_STATE, STEP_DELIVERY_ADDRESS, STEP_DELIVERY_METHOD } from './CheckoutForm';
import { useFetch } from '../../../CustomHook/fetch/useFetch';
import '../../../styles/Checkout/index.css';
import '../../../styles/Checkout/cartSummary.css';
import '../../../styles/Checkout/checkoutForm.css';
import { Security } from '../../../Config/Security';
import { useProgressiveSteps } from '../../../CustomHook/useProgressiveSteps';



const checkoutInitialData = {
    civilState: {
        email: '',
        civility: '',
        firstName: '',
        lastName: ''
    },
    deliveryAddress: {
        civility: '',
        firstName: '',
        lastName: '',
        lineOne: '',
        lineTwo: '',
        postcode: '',
        city: '',
        country: '',
        enCountry: '',
        iso: '',
        continents: ''
    },
    deliveryMethod: null,
    customsFeesAlert: null,
    invoiceAddress: {
        lineOne: '',
        lineTwo: '',
        postcode: '',
        city: '',
        country: '',
        enCountry: '',
        iso: '',
        continents: ''
    },
    numberOfArticles: null
}

export const Checkout = () => {

    
    // ce hook sauvegarde step et maxStep à chaque changement dans le localStorage, et récupère la valeur à l'initialisation
    const [step, selectStep, forwardStep] = useProgressiveSteps(STEP_CIVIL_STATE); 

    //cartSummary
    const [cart, cartIsLoading, cartErrors] = useFetch('/fr/api/cart/getFullCart');


    const [checkoutData, setCheckoutData] = useState(checkoutInitialData);

    //user from database
    const [user, loading, errors] = useFetch('/fr/api/user/getCivilState');
    
    //récupération des données présentes dans localStorage
    useEffect(() => {
        if(window.sessionStorage.getItem('checkout')) {                          
            setCheckoutData(
                Security.decryptToObject(window.sessionStorage.getItem('checkout'))
            );
        }
    }, []);
    // ou dans user from database
    useEffect(() => {
        //si le civilState n'est pas sauvegardé dans localStorage on entre les données de la database
        if(user && checkoutData.civilState === checkoutInitialData.civilState) {
            if(user.civility && user.firstName && user.lastName) {
                selectStep(STEP_DELIVERY_ADDRESS);
            }
            setCheckoutData(checkoutData => ({
                ...checkoutData,
                civilState: user,
            }));
        };
    }, [user]);

    //à chaque changement de checkoutData on persiste les données dans localStorage
    useEffect(() => {
        if(checkoutData !== checkoutInitialData) {
            window.sessionStorage.setItem('checkout', Security.encryptFromObject(checkoutData));            
        }
    }, [checkoutData]);

    // numberOfArticles (vérif de la validité de deliveryMethod)
    useEffect(() => {
        if(cart) {
            if(checkoutData.deliveryMethod && cart.count !== checkoutData.numberOfArticles) {
                selectStep(STEP_DELIVERY_METHOD);
            }
            setCheckoutData(checkoutData => ({
                ...checkoutData,
                numberOfArticles: cart.count
            }));
        }
    }, [cart]);

    return (
        <div className="checkout">
            <div className="checkout-container">
                <CheckoutForm 
                    cart={cart} 
                    loading={loading} 
                    checkoutData={checkoutData} 
                    setCheckoutData={setCheckoutData} 
                    step={step} 
                    selectStep={selectStep} 
                    forwardStep={forwardStep}
                    />
                <CartSummary cart={cart} shippingCost={checkoutData.deliveryMethod?.price} loading={cartIsLoading} errors={cartErrors} />
            </div>
        </div>
    )
}

