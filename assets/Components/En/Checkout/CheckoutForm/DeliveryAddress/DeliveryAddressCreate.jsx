import React, { useState } from 'react';
import { useFormWithValidation } from '../../../../../CustomHook/form/useFormWithValidation';
import { DeliveryAddressForm, deliveryAddressSchema } from './DeliveryAddressForm';



const emptyDeliveryAddress = {
    civility: '',
    firstName: '',
    lastName: '',
    lineOne: '',
    lineTwo: '',
    postcode: '',
    city: '',
    country: '',
    enCountry: '',
    iso: null,
    continents: []
};



/**
 * 
 * @param {Object} deliveryAddress (defaultValues)
 * @returns 
 */
export const DeliveryAddressCreate = ({mainSubmit, create, onCancel, onServerError}) => {
    const { handleSubmit, errors, isSubmitting, setValue, setError, control } = useFormWithValidation(deliveryAddressSchema, emptyDeliveryAddress);
    
    const [isLoading, setLoading] = useState(false);
    const onSubmit = async formData => {
        if(isLoading) {
            return;
        }
        if(!formData.iso) {
            setError('enCountry', {type: 'custom', message: 'Please choose a country among those proposed'});
            return;
        }
        setLoading(true);
        try {
            await create(formData); 
            mainSubmit(formData);
        } catch(e) {
            onServerError(e);
        }
        setLoading(false);
    };

    return (
        <DeliveryAddressForm 
            onSubmit={handleSubmit(onSubmit)} 
            errors={errors} 
            isLoading={isLoading || isSubmitting}
            onCancel={onCancel} 
            setValue={setValue}
            control={control}
        />
    )
}