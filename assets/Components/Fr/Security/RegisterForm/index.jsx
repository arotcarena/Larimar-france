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
    email: yup.string().email('Adresse email invalide').max(200, '200 caractères maximum').required('L\'adresse email est obligatoire'),
    plainPassword: yup.string().min(6, '6 caractères minimum').max(200, '200 caractères maximum').required('Le mot de passe est obligatoire'),
    passwordConfirm: yup.string().required('La confirmation du mot de passe est obligatoire'),
    agreeTerms: yup.bool().isTrue('Vous devez cocher cette case')
  }).required();



export const RegisterForm = ({csrfToken}) => {
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
          setError('passwordConfirm', {type: 'custom', message: 'Les deux mots de passe ne sont pas identiques'});
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
              setError('email', {type: 'custom', message: 'Cette adresse email correspond à un compte déjà existant'});
        }
    };


    return (
        <form ref={formRef} method="post" onSubmit={handleSubmit(onSubmit)}>

            <TextField control={control} name="email" error={errors.email?.message} maxLength={200}>Adresse email *</TextField>
            
            <PasswordField control={control} name="plainPassword" error={errors.plainPassword?.message} maxLength={200}>Mot de passe *</PasswordField>

            <PasswordField control={control} name="passwordConfirm" error={errors.passwordConfirm?.message} maxLength={200}>Confirmez le mot de passe *</PasswordField>

            <div className={'checkbox-group' + (errors.agreeTerms ? ' is-invalid': '')}>
                <input {...register('agreeTerms')} className="form-checkbox" id="checkboxRememberMe" type="checkbox" />
                <label htmlFor="checkboxRememberMe" className="form-label">
                    <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                        <CheckIcon />
                    </div>
                    <span id="checkbox-label">J'accepte les conditions générales d'utilisation *</span>
                </label>
                {
                    errors.agreeTerms && <div className="form-error">{errors.agreeTerms.message}</div>
                }
            </div>
        
            <input type="hidden" name="_token" value={csrfToken} />

            <div className="submit-group">
                <FormButton loading={isLoading || isSubmitting} additionalClass="security-button">Créer un compte</FormButton>
            </div>
        </form>
    )
}