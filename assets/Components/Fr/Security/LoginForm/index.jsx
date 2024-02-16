import React, { useRef, useState } from 'react';
import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';
import * as yup from "yup";
import { Loader } from '../../../../UI/Icon/Loader';
import { useLabelDown } from '../../../../CustomHook/form/useLabelDown';
import { CheckIcon } from '../../../../UI/Icon/CheckIcon';
import { useFormWithValidation } from '../../../../CustomHook/form/useFormWithValidation';
import { TextField } from '../../../../UI/Form/TextField';
import { PasswordField } from '../../../../UI/Form/PasswordField';
import { FormButton } from '../../../../UI/Form/FormButton';

const schema = yup.object({
    email: yup.string().required('L\'adresse email est obligatoire'),
    password: yup.string().required('Le mot de passe est obligatoire')
  }).required();



export const LoginForm = ({lastUsername, csrfToken}) => {

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

    return (
        <form ref={formRef} method="post" onSubmit={handleSubmit(onSubmit)}>
            <TextField control={control} name="email" error={errors.email?.message} maxLength={200}>Adresse email *</TextField>

            <PasswordField control={control} name="password" error={errors.password?.message} maxLength={200}>Mot de passe *</PasswordField>

           
            <div className="checkbox-group">
                <input className="form-checkbox" id="checkboxRememberMe" type="checkbox" name="_remember_me" />
                <label htmlFor="checkboxRememberMe" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">Se souvenir de moi</span>
                </label>
            </div>
        
            <input type="hidden" name="_csrf_token" value={csrfToken} />

            <div className="link-group">
                <a className="form-link" href="/fr/mot-de-passe-oublié">Mot de passe oublié ?</a>
            </div>
            <div className="link-group">
                <a className="form-link" href="/fr/créer-un-compte">Pas encore inscrit ? Cliquez ici pour créer un compte</a>
            </div>

            <div className="submit-group">
                <FormButton loading={isLoading || isSubmitting} additionalClass="security-button">Se connecter</FormButton>
            </div>
        </form>
    )
}