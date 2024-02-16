import React, { useRef, useState } from 'react';
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";
import { Loader } from '../../../../UI/Icon/Loader';
import { useLabelDown } from '../../../../CustomHook/form/useLabelDown';
import { CheckIcon } from '../../../../UI/Icon/CheckIcon';
import { TextField } from '../../../../UI/Form/TextField';
import { PasswordField } from '../../../../UI/Form/PasswordField';
import { FormButton } from '../../../../UI/Form/FormButton';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';

const schema = yup.object({
    email: yup.string().required('Email address is required'),
    password: yup.string().required('Password is required')
  }).required();



export const LoginForm = ({lastUsername, csrfToken}) => {

    //validation et loading
    const formRef = useRef(null);
    const [isLoading, setIsLoading] = useState(false);

    const { handleSubmit, errors, isSubmitting, control } = useFormWithValidation(schema, {
        email: lastUsername ?? '',
        password: ''
    });

    const onSubmit = data => {
        if(isLoading) {
            return;
        }
        formRef.current.submit();
        setIsLoading(true);
    };

    //gestion du label down
    const emailWrapper = useRef(null);
    useLabelDown(emailWrapper);
    const passwordWrapper = useRef(null); 
    useLabelDown(passwordWrapper);

    return (
        <form ref={formRef} method="post" onSubmit={handleSubmit(onSubmit)}>
            
            <TextField control={control} name="email" error={errors.email?.message} maxLength={200}>Email address *</TextField>

            <PasswordField control={control} name="password" error={errors.password?.message} maxLength={200}>Password *</PasswordField>

            <div className="checkbox-group">
                <input className="form-checkbox" id="checkboxRememberMe" type="checkbox" name="_remember_me" />
                <label htmlFor="checkboxRememberMe" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">Remember me</span>
                </label>
            </div>
        
            <input type="hidden" name="_csrf_token" value={csrfToken} />

            <div className="link-group">
                <a className="form-link" href="/en/forgotten-password">Forgot your password ?</a>
            </div>
            <div className="link-group">
                <a className="form-link" href="/en/create-my-account">Not registered ? Click here to create an account</a>
            </div>

            <div className="submit-group">
                <FormButton loading={isLoading || isSubmitting} additionalClass="security-button">Log in</FormButton>
            </div>
        </form>
    )
}