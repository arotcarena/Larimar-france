import React, { useState } from 'react';
import * as yup from "yup"
import { TextConfig } from '../../../../Config/TextConfig';
import { ApiError, apiFetch } from '../../../../functions/api';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';
import { TextField } from '../../../../UI/Form/TextField';
import { RadioFields } from '../../../../UI/Form/RadioField';
import { FormButton } from '../../../../UI/Form/FormButton';
import { EnTextConfig } from '../../../../Config/EnTextConfig';
import { createToken } from '../../../../functions/token';

const schema = yup
  .object({
    civility: yup.string().test('custom-validation', 'Incorrect value', (value) => {
        return [TextConfig.CIVILITY_F, TextConfig.CIVILITY_M].includes(value);
    }).required('This field is required'),
    firstName: yup.string().max(200, '200 characters max.').required('First name is required'),
    lastName: yup.string().max(200, '200 characters max.').required('Last Name is required'),
    email: yup.string().email('Invalid email address').max(200, '200 characters max.').required('Email address is required')
  })
  .required()

export const ProfileForm = ({user, setUser, close}) => {
    const {handleSubmit, control, errors, setError, isSubmitting} = useFormWithValidation(schema, {
        ...user,
        emailChangeToken: ''
    });

    const [isLoading, setLoading] = useState(false);
    const [serverError, setServerError] = useState(false);
    
    const [emailChangeToken, setEmailChangeToken] = useState(null);
    const [readOnlyEmail, setReadOnlyEmail] = useState(null);

    const onSubmit = async formData => {
        if(isLoading) {
            return;
        }
        setLoading(true);
        //vérification du nouveau mail
        //si on essaie de modifier le mail
        if(formData.email !== user.email && !emailChangeToken) {
            const token = createToken(6);
            // envoi du code de vérification
            try {
                await apiFetch('/en/api/security/changeEmailConfirmationEmail', {
                    method: 'POST',
                    body: JSON.stringify({
                        token: token, 
                        email: formData.email
                    })
                });
                setEmailChangeToken(token);
                setReadOnlyEmail(formData.email);
                setError('emailChangeToken', {type: 'custom', message: 'Please enter the 6-digit code sent at '+formData.email})
            } catch(e) {
                setError('email', {type: 'custom', message: 'An error has occurred. Please try again later'});
            }
            setLoading(false);
            return;
        }
        //si on a déjà reçu le code
        if(formData.email !== user.email && emailChangeToken) {
            //si le code est faux on return
            if(formData.emailChangeToken !== emailChangeToken) {
                setError('emailChangeToken', {type: 'custom', message: 'Invalid code'})
                setLoading(false);
                return;
            }
        }
        //sauvegarde dans db
        setServerError(null);
        try {
            await apiFetch('/en/api/user/setCivilState', {
                method: 'POST',
                body: JSON.stringify(formData)
            });
            setUser(formData);
            close();
        } catch(e) {
            if(e instanceof ApiError) {
                setServerError(e.errors[0])
            } else {
                setServerError('Vos modifications n\'ont pas pu être sauvegardées. Veuillez réessayer ultérieurement');
            }
        }
        setLoading(false);
    } 

    return (
        <form className="account-profile-form" onSubmit={handleSubmit(onSubmit)}>
            {
                serverError && <div className="form-error">{serverError}</div>
            }

            <RadioFields
                control={control}
                name="civility"
                choices={{
                    [EnTextConfig.CIVILITY_M]: TextConfig.CIVILITY_M,
                    [EnTextConfig.CIVILITY_F]: TextConfig.CIVILITY_F
                }}
                selected={user.civility}
                error={errors.civility?.message}
            />

            <TextField control={control} name="firstName" error={errors.firstName?.message} additionalClass="capitalize">First name</TextField>

            <TextField control={control} name="lastName" error={errors.lastName?.message} additionalClass="capitalize">Last name</TextField>

            {
                !emailChangeToken && (
                    <TextField control={control} name="email" error={errors.email?.message}>Email address</TextField>
                ) 
            }

            {
                emailChangeToken && (
                    <>
                        <div className="form-group">
                            <div className="input-wrapper">
                                <label className="form-label">Email address</label>
                                <div className="form-control">{readOnlyEmail}</div>
                            </div>
                        </div>
                        <TextField control={control} name="emailChangeToken" error={errors.emailChangeToken?.message}>Confirmation code</TextField>
                    </>
                )
            }

            <div className="submit-group">
                <FormButton loading={isSubmitting || isLoading}>Submit</FormButton>
                <button 
                    className={'form-button secondary' + (isSubmitting || isLoading ? ' disabled': '')}
                    onClick={close} 
                    disabled={isSubmitting || isLoading}
                    >
                    Cancel
                </button>
            </div>
        </form>
    )
}