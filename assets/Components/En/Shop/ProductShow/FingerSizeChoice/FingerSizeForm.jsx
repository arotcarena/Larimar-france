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
    email: yup.string().email('Invalid email address').max(200, '200 characters max.').required('You must provide your email'),
    fingerSize: yup.string().required('Yous must provide your ring size'),
    agree: yup.bool().isTrue('You must check this box')
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
            await apiFetch('/en/api/contact/fingerSize', {
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
            <h1 className="fingerSize-title">Size request</h1>
            {
                error && <div className="form-error">The form is invalid</div>
            }
            <TextField name="email" control={control} error={errors.email?.message} maxLength="200">
                Email address
            </TextField>
            <TextField type="number" name="fingerSize" control={control} error={errors.fingerSize?.message}>
                Ring size
            </TextField>

            <div className={'checkbox-group' + (errors.agree ? ' is-invalid': '')}>
                <input {...register('agree')} className="form-checkbox" id="agree" type="checkbox" />
                <label htmlFor="agree" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">I agree to be contacted by Larimar France</span>
                </label>
                {
                    errors.agree && <div className="form-error">{errors.agree.message}</div>
                }
            </div>
            <FormButton loading={isLoading || isSubmitting} lang="fr">Send</FormButton>
        </form>
    )
}