import React, { useState } from 'react';
import { useFormWithValidation } from '../../../../../CustomHook/form/useFormWithValidation';
import { RadioFields } from '../../../../../UI/Form/RadioField';
import { TextField } from '../../../../../UI/Form/TextField';
import { FormButton } from '../../../../../UI/Form/FormButton';
import * as yup from "yup";
import { apiFetch } from '../../../../../functions/api';
import { EnTextConfig } from '../../../../../Config/EnTextConfig';
import { TextConfig } from '../../../../../Config/TextConfig';


const civilStateSchema = yup.object({
    civility: yup.string().required('This field is required').test('custom-validation', 'Incorrect value', (value) => {
        return [TextConfig.CIVILITY_F, TextConfig.CIVILITY_M].includes(value);
    }),
    firstName: yup.string().max(200, '200 characters max.').required('First name is required'),
    lastName: yup.string().max(200, '200 characters max.').required('Last name is required')
}).required();





export const CivilStateForm = ({civilState, setCheckoutData, forwardStep}) => {
    const { handleSubmit, errors, isSubmitting, control } = useFormWithValidation(civilStateSchema, {
        civility: civilState.civility,
        firstName: civilState.firstName,
        lastName: civilState.lastName
    });
    const [isLoading, setLoading] = useState(false);
    const [serverError, setServerError] = useState(false);
    const onSubmit = async formData => {
        if(isLoading) {
            return;
        }
        setLoading(true);
        setServerError(false);
        try {
            await apiFetch('/en/api/user/setCivilState', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            });
            setCheckoutData(checkoutData => ({
                ...checkoutData,
                civilState: {
                    email: civilState.email,
                    civility: formData.civility,
                    firstName: formData.firstName,
                    lastName: formData.lastName
                }
            }));
            forwardStep();
        } catch(e) {
            setServerError(true);
        }
        setLoading(false);
    };



    return (
        <form onSubmit={handleSubmit(onSubmit)}>
            <div className="info-group">
                Email address : {civilState.email}
            </div>
            {
                serverError && <div className="form-error">Le formulaire est invalide</div>
            }
            <RadioFields 
                        control={control} 
                        name="civility" 
                        error={errors.civility?.message}
                        choices={{
                            [EnTextConfig.CIVILITY_M]: TextConfig.CIVILITY_M,
                            [EnTextConfig.CIVILITY_F]: TextConfig.CIVILITY_F,
                        }}
                        selected={civilState.civility}
            >
                <div className="asterix">*</div>
            </RadioFields>
            
            <TextField control={control} name="firstName" error={errors.firstName?.message} additionalClass="capitalize" maxLength={200}>First name *</TextField>
            
            <TextField control={control} name="lastName" error={errors.lastName?.message} additionalClass="capitalize" maxLength={200}>Last name *</TextField>

            <div className="submit-group">
                <FormButton loading={isSubmitting || isLoading}>Submit</FormButton>
            </div>
        </form>
    )
}