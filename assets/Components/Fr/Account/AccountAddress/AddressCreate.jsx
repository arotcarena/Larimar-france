import React, { useState } from 'react';
import { AddressForm, addressSchema } from './AddressForm';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';



const emptyAddress = {
    civility: '',
    firstName: '',
    lastName: '',
    lineOne: '',
    lineTwo: '',
    postcode: '',
    city: '',
    country: '',
    iso: null,
    continents: []
};



/**
 * 
 * @param {Object} deliveryAddress (defaultValues)
 * @returns 
 */
export const AddressCreate = ({create, close, onServerError}) => {
    const { handleSubmit, errors, isSubmitting, setValue, setError, control } = useFormWithValidation(addressSchema, emptyAddress);
    
    const [isLoading, setLoading] = useState(false);
    const onSubmit = async formData => {
        if(isLoading) {
            return;
        }
        if(!formData.iso) {
            setError('country', {type: 'custom', message: 'Veuillez choisir un pays parmis ceux proposés'});
            return;
        }
        setLoading(true);
        try {
            await create(formData); 
        } catch(e) {
            onServerError(e);
        }
        setLoading(false);
        close();
    };

    return (
        <AddressForm 
            onSubmit={handleSubmit(onSubmit)} 
            errors={errors} 
            isLoading={isLoading || isSubmitting}
            onCancel={close} 
            setValue={setValue}
            control={control}
        />
    )
}