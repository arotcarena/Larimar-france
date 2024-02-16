import React, { useRef, useState } from 'react';
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";
import { apiFetch } from '../../../../functions/api';
import { Loader } from '../../../../UI/Icon/Loader';
import { useLabelDown } from '../../../../CustomHook/form/useLabelDown';
import { CheckIcon } from '../../../../UI/Icon/CheckIcon';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';
import { TextField } from '../../../../UI/Form/TextField';
import { PasswordField } from '../../../../UI/Form/PasswordField';
import { FormButton } from '../../../../UI/Form/FormButton';

const schema = yup.object({
    email: yup.string().email('Invalid email address').max(200, '200 characters max').required('Email address is required'),
    plainPassword: yup.string().min(6, 'At least 6 characters').max(200, '200 characters max').required('Password is required'),
    passwordConfirm: yup.string().required('Password confirmation is required'),
    agreeTerms: yup.bool().isTrue('You must check this box')
  }).required();



export const RegisterForm = ({csrfToken}) => {
    //validation et loading
    const formRef = useRef(null);
    const [isLoading, setIsLoading] = useState(false);

    const { register, handleSubmit, setError, errors, isSubmitting, control } = useFormWithValidation(schema, {
        email: '',
        plainPassword: '',
        passwordConfirm: ''
    });

    const onSubmit = async data => {
        if(isLoading) {
            return;
        }
        if(data.plainPassword !== data.passwordConfirm) {
          setError('passwordConfirm', {type: 'custom', message: 'The 2 passwords are not identical'});
          return;
        }
        try {
            await apiFetch('/api/security/registration/uniqueEmailValidation', {
              method: 'POST',
              body: JSON.stringify(data.email)
            });
            formRef.current.submit();
            setIsLoading(true);
        } catch(e) {
              setError('email', {type: 'custom', message: 'This email address corresponds to an existing account'});
        }
    };

    //gestion du label down
    const emailWrapper = useRef(null);
    useLabelDown(emailWrapper);
    const plainPasswordWrapper = useRef(null);
    useLabelDown(plainPasswordWrapper); 
    const passwordConfirmWrapper = useRef(null);
    useLabelDown(passwordConfirmWrapper);

    return (
        <form ref={formRef} method="post" onSubmit={handleSubmit(onSubmit)}>

            <TextField control={control} name="email" error={errors.email?.message} maxLength={200}>Email address *</TextField>
            
            <PasswordField control={control} name="plainPassword" error={errors.plainPassword?.message} maxLength={200}>Password *</PasswordField>

            <PasswordField control={control} name="passwordConfirm" error={errors.passwordConfirm?.message} maxLength={200}>Confirm the password *</PasswordField>

            <div className={'checkbox-group' + (errors.agreeTerms ? ' is-invalid': '')}>
                <input {...register('agreeTerms')} className="form-checkbox" id="checkboxRememberMe" type="checkbox" />
                <label htmlFor="checkboxRememberMe" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">I agree terms of service</span>
                </label>
                {
                    errors.agreeTerms && <div className="form-error">{errors.agreeTerms.message}</div>
                }
            </div>
        
            <input type="hidden" name="_token" value={csrfToken} />

            <div className="submit-group">
                <FormButton loading={isLoading || isSubmitting} additionalClass="security-button">Create an account</FormButton>
            </div>
        </form>
    )
}