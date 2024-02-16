import React, { useEffect, useState } from 'react';
import { apiFetch } from '../../../../../functions/api';
import { useForm } from 'react-hook-form';
import { yupResolver } from "@hookform/resolvers/yup"
import * as yup from "yup"
import { RadioField } from '../../../../../UI/Form/RadioField';
import { FormButton } from '../../../../../UI/Form/FormButton';
import { priceFormater } from '../../../../../functions/formaters';
import { SiteConfig } from '../../../../../Config/SiteConfig';
import { Loader } from '../../../../../UI/Icon/Loader';
import { deliveryTimeLabel } from '.';


const schema = yup.object({
    deliveryMethod: yup.number().required('Veuillez choisir parmis les méthodes proposées'),
}).required();


export const DeliveryMethodForm = ({choices, setChoices, deliveryAddress, cart, deliveryMethod, setCheckoutData, forwardStep, setChoosingRelay}) => {

    const { control, handleSubmit, formState: { errors, isSubmitting } } = useForm({
        defaultValue: {
            deliveryMethod: deliveryMethod
        },
        resolver: yupResolver(schema),
        mode: 'onTouched'
    });

    const [isLoading, setLoading] = useState(false);
    const onSubmit = formData => {
        if(isLoading) {
            return;
        }
        setLoading(true);
        const selectedDeliveryMethod = choices.find(choice => formData.deliveryMethod === choice.id);
        setCheckoutData(checkoutData => ({
            ...checkoutData,
            deliveryMethod: selectedDeliveryMethod,
        }));
        if(selectedDeliveryMethod.mode === SiteConfig.DELIVERY_MODE_RELAY) {
            setChoosingRelay(true);
            return;
        }
        forwardStep();
    };


    const [choicesAreLoading, setChoicesLoading] = useState(false);
    const [choicesError, setChoicesError] = useState(false);
    useEffect(() => {
        (async () => {
            if(choices === null) {
                setCheckoutData(checkoutData => ({
                    ...checkoutData,
                    customsFeesAlert: null
                }));
                setChoicesLoading(true);

                try {
                    const {deliveryMethods, customsFeesAlert} = await apiFetch('/fr/api/deliveryMethod/choices', {
                        method: 'POST',
                        body: JSON.stringify({
                            deliveryAddress: deliveryAddress,
                            count: cart?.count
                        })
                    });
                    setChoices(deliveryMethods);
                    setCheckoutData(checkoutData => ({
                        ...checkoutData,
                        customsFeesAlert: customsFeesAlert
                    }));
                } catch(e) {
                    setChoicesError(true);
                }
                setChoicesLoading(false);
            }
        })();
    }, []);


    if(choicesError) {
        return (
            <div className="form-error">
                Une erreur est survenue. Veuillez réactualiser la page
            </div>
        )
    }

    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            {
                choicesAreLoading && <Loader />
            }
            <div className="form-group">
                {
                    choices && choices.map(deliveryMethod => (
                        <RadioField 
                            key={deliveryMethod.id} 
                            control={control} 
                            name="deliveryMethod" 
                            value={deliveryMethod.id} 
                            error={errors.deliveryMethod?.message}
                            additionalClass="margin info-row"
                            >
                            <div className="info-row">
                                <span>{deliveryMethod.name}</span>
                                <span>{priceFormater(deliveryMethod.price)}</span> 
                                {
                                    deliveryMethod.deliveryTime && (
                                        <span className="text-small">
                                            ({(deliveryTimeLabel(deliveryMethod.deliveryTime))})
                                        </span>
                                    )
                                }
                            </div>
                        </RadioField>
                    ))
                }
                {errors.deliveryMethod && <div className="form-error">{errors.deliveryMethod.message}</div>}
            </div>
            <div className="form-submit">
                <FormButton loading={isSubmitting || isLoading}>Valider</FormButton>
            </div>
        </form>
    )
}


