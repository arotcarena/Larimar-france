import React, { useRef } from 'react';
import * as yup from "yup";
import { FormButton } from '../../../../../UI/Form/FormButton';
import { TextField } from '../../../../../UI/Form/TextField';
import { RadioFields } from '../../../../../UI/Form/RadioField';
import { EnTextConfig } from '../../../../../Config/EnTextConfig';
import { TextConfig } from '../../../../../Config/TextConfig';
import { TextFieldWithSuggest } from '../../../../../UI/Form/TextFieldWithSuggest';
import { AddressSuggestItem } from '../../../../../UI/AddressSuggestItem';
import { useMapSearch } from '../../../../../CustomHook/fetch/useMapSearch';
import { useCountrySearch } from '../../../../../CustomHook/fetch/useCountrySearch';
import { CountrySuggestItem } from '../../../../../UI/CountrySuggestItem';


export const deliveryAddressSchema = yup.object({
    civility: yup.string().required('This field is required').test('custom-validation', 'Incorrect value', (value) => {
        return [TextConfig.CIVILITY_F, TextConfig.CIVILITY_M].includes(value);
    }),
    firstName: yup.string().max(200, '200 characters max.').required('First name is required'),
    lastName: yup.string().max(200, '200 characters max.').required('Last name is required'),
    lineOne: yup.string().max(200, '200 characters max.').required('Street address is required'),
    lineTwo: yup.string().max(200, '200 characters max.').nullable(),
    postcode: yup.string().max(20, '20 characters max.').required('Postal code is required'),
    city: yup.string().max(200, '200 characters max.').required('City is required'),
    enCountry: yup.string().max(200, '200 characters max.').required('Country is required'),
}).required();


export const DeliveryAddressForm = ({onSubmit, errors, isLoading, onCancel, setValue, control, address}) => {

    //on address suggest selection
    const handleSelect = (address) => {
        setValue('lineOne', address.lineOne);
        setValue('lineTwo', '');
        setValue('postcode', address.postcode);
        setValue('city', address.city);
        setValue('country', address.country);
        setValue('enCountry', address.enCountry);
        setValue('iso', address.iso);
        setValue('continents', address.continents);
        formRef.current.querySelectorAll('.input-wrapper').forEach(inputWrapper => {
            //on enlève le labelDown sur tous les champs qu'on remplit
            if(!inputWrapper.classList.contains('lineTwo')) {
                inputWrapper.classList.remove('down');
            }
        })
    }
    const formRef = useRef(null);

    //on country suggest selection
    const handleCountrySelect = country => {
        setValue('country', country.name);
        setValue('enCountry', country.enName);
        setValue('iso', country.iso);
        setValue('continents', country.continents);
    }

    //supprime iso si on modifie country sans utiliser suggestion
    const handleCountryChange = () => {
        setValue('iso', null);
        setValue('continents', []);
    }

    return (
        <form ref={formRef} onSubmit={onSubmit}>

            <RadioFields 
                        control={control} 
                        name="civility" 
                        error={errors.civility?.message}
                        choices={{
                            [EnTextConfig.CIVILITY_M]: TextConfig.CIVILITY_M,
                            [EnTextConfig.CIVILITY_F]: TextConfig.CIVILITY_F,
                        }}
                        selected={address?.civility}
            >
                <div className="asterix">*</div>
            </RadioFields>

            <TextField control={control} name="firstName" error={errors.firstName?.message} additionalClass="capitalize" maxLength={200}>First name *</TextField>
            
            <TextField control={control} name="lastName" error={errors.lastName?.message} additionalClass="capitalize" maxLength={200}>Last name *</TextField>

            <TextFieldWithSuggest control={control} customHookFetcher={useMapSearch} name="lineOne" onSelect={handleSelect} error={errors.lineOne?.message} maxLength={200} 
                    render={(address, isSelected, onSelect) => (
                        <AddressSuggestItem key={address.id} address={address} selected={isSelected} onSelect={onSelect} />
                    )}
                >
                Street address *
            </TextFieldWithSuggest>

            <TextField control={control} name="lineTwo" error={errors.lineTwo?.message} maxLength={200}>Additional information (building, etc...)</TextField>

            <TextField control={control} name="postcode" error={errors.postcode?.message} maxLength={200}>Postal code *</TextField>

            <TextField control={control} name="city" error={errors.city?.message} maxLength={200}>City *</TextField>

            <TextFieldWithSuggest 
                control={control} 
                name="enCountry" 
                customHookFetcher={useCountrySearch} 
                onSelect={handleCountrySelect} 
                error={errors.country?.message} 
                maxLength={200}
                onChange={handleCountryChange}
                render={(country, isSelected, onSelect) => (
                    <CountrySuggestItem key={country.id} country={country} selected={isSelected} onSelect={onSelect} lang="en" />
                )}
                >
                Country *
            </TextFieldWithSuggest>

            <FormButton loading={isLoading}>Submit</FormButton>
            <button  className={'form-button secondary' + (isLoading ? ' disabled': '')} onClick={onCancel} disabled={isLoading}>Cancel</button>
        </form>
    )
}
