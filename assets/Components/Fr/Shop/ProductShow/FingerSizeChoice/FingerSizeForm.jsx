import React, { useState } from 'react';
import { useForm } from "react-hook-form"
import { yupResolver } from "@hookform/resolvers/yup"
import * as yup from "yup"
import { TextField } from '../../../../../UI/Form/TextField';
import { FormButton } from '../../../../../UI/Form/FormButton';
import { apiFetch } from '../../../../../functions/api';
import { CheckIcon } from '../../../../../UI/Icon/CheckIcon';

const schema = yup
  .object({
    email: yup.string().email('Adresse email invalide').max(200, '200 caractères max.').required('Vous devez renseigner votre email'),
    fingerSize: yup.string().required('Vous devez renseigner votre taille de doigt'),
    agree: yup.bool().isTrue('Vous devez cocher cette case')
  })
  .required()

export const FingerSizeForm = ({publicRef, onSuccess}) => {
   
    const { register, control, handleSubmit, formState: {errors, isSubmitting}} = useForm({
        defaultValues: {
            email: '',
            fingerSize: '',
            agree: false
        },
        resolver: yupResolver(schema),
        mode: 'onTouched'
    });

    const [isLoading, setLoading] = useState(false);
    const [error, setError] = useState(false);

    const onSubmit = async formData => {
        if(isLoading) {
            return;
        }
        setError(false);
        setLoading(true);
        try {
            await apiFetch('/fr/api/contact/fingerSize', {
                method: 'POST',
                body: JSON.stringify({
                    ...formData,
                    publicRef: publicRef
                })
            });
            onSuccess();
        } catch(e) {
            setError(true);
        }
        setLoading(false);
    }

    return (
        <form onSubmit={handleSubmit(onSubmit)} className="fingerSize-form">
            <h1 className="fingerSize-title">Demander ma taille</h1>
            {
                error && <div className="form-error">Le formulaire est invalide</div>
            }
            <TextField name="email" control={control} error={errors.email?.message} maxLength="200">
                Adresse email
            </TextField>
            <TextField type="number" name="fingerSize" control={control} error={errors.fingerSize?.message}>
                Taille de doigt
            </TextField>

            <div className={'checkbox-group' + (errors.agree ? ' is-invalid': '')}>
                <input {...register('agree')} className="form-checkbox" id="agree" type="checkbox" />
                <label htmlFor="agree" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">J'accepte d'être recontacté(e) par Larimar France</span>
                </label>
                {
                    errors.agree && <div className="form-error">{errors.agree.message}</div>
                }
            </div>
            <FormButton loading={isLoading || isSubmitting} lang="fr">Envoyer</FormButton>
        </form>
    )
}