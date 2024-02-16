import React, { useState } from 'react';
import { AddressForm, addressSchema } from './AddressForm';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';


/**
 * 
 * @param {Object} deliveryAddress (defaultValues)
 * @returns 
 */
export const AddressUpdate = ({update, address, close, onServerError}) => {
    const { handleSubmit, errors, isSubmitting, setValue, setError, control } = useFormWithValidation(addressSchema, address);

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
            await update(formData, address.id); 
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
            address={address}
        />
    )
}