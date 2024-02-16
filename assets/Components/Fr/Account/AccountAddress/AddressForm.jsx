import React, { useRef } from 'react';
import * as yup from "yup";
import { RadioFields } from '../../../../UI/Form/RadioField';
import { TextField } from '../../../../UI/Form/TextField';
import { FormButton } from '../../../../UI/Form/FormButton';
import { TextConfig } from '../../../../Config/TextConfig';
import { AddressSuggestItem } from '../../../../UI/AddressSuggestItem';
import { TextFieldWithSuggest } from '../../../../UI/Form/TextFieldWithSuggest';
import { useMapSearch } from '../../../../CustomHook/fetch/useMapSearch';
import { useCountrySearch } from '../../../../CustomHook/fetch/useCountrySearch';
import { CountrySuggestItem } from '../../../../UI/CountrySuggestItem';


export const addressSchema = yup.object({
    civility: yup.string().required('La civilité est obligatoire').test('custom-validation', 'Valeur incorrecte', (value) => {
        return [TextConfig.CIVILITY_F, TextConfig.CIVILITY_M].includes(value);
    }),
    firstName: yup.string().max(200, '200 caractères max.').required('Le prénom est obligatoire'),
    lastName: yup.string().max(200, '200 caractères max.').required('Le nom est obligatoire'),
    lineOne: yup.string().max(200, '200 caractères max.').required('L\'adresse est obligatoire'),
    lineTwo: yup.string().max(200, '200 caractères max.').nullable(),
    postcode: yup.string().max(20, '20 caractères max.').required('Le code postal est obligatoire'),
    city: yup.string().max(200, '200 caractères max.').required('La ville est obligatoire'),
    country: yup.string().max(200, '200 caractères max.').required('Le pays est obligatoire'),
}).required();


export const AddressForm = ({onSubmit, errors, isLoading, onCancel, setValue, control, address}) => {

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
                            [TextConfig.CIVILITY_M]: TextConfig.CIVILITY_M,
                            [TextConfig.CIVILITY_F]: TextConfig.CIVILITY_F,
                        }}
                        selected={address?.civility}
            >
                <div className="asterix">*</div>
            </RadioFields>

            <TextField control={control} name="firstName" error={errors.firstName?.message} additionalClass="capitalize" maxLength={200}>Prénom *</TextField>
            
            <TextField control={control} name="lastName" error={errors.lastName?.message} additionalClass="capitalize" maxLength={200}>Nom *</TextField>


            <TextFieldWithSuggest control={control} customHookFetcher={useMapSearch} name="lineOne" onSelect={handleSelect} error={errors.lineOne?.message} maxLength={200} 
                    render={(address, isSelected, onSelect) => (
                        <AddressSuggestItem key={address.id} address={address} selected={isSelected} onSelect={onSelect} />
                    )}
                >
                Numéro et voie *
            </TextFieldWithSuggest>
            

            <TextField control={control} name="lineTwo" error={errors.lineTwo?.message} maxLength={200}>Complément (bâtiment, etc...)</TextField>

            <TextField control={control} name="postcode" error={errors.postcode?.message} maxLength={200}>Code postal *</TextField>

            <TextField control={control} name="city" error={errors.city?.message} maxLength={200}>Ville *</TextField>

            <TextFieldWithSuggest 
                control={control} 
                name="country" 
                customHookFetcher={useCountrySearch} 
                onSelect={handleCountrySelect} 
                error={errors.country?.message} 
                maxLength={200}
                onChange={handleCountryChange}
                render={(country, isSelected, onSelect) => (
                    <CountrySuggestItem key={country.id} country={country} selected={isSelected} onSelect={onSelect} />
                )}
                >
                Pays *
            </TextFieldWithSuggest>

            <FormButton loading={isLoading}>Valider</FormButton>
            <button  className={'form-button secondary' + (isLoading ? ' disabled': '')} onClick={onCancel} disabled={isLoading}>Annuler</button>
        </form>
    )
}