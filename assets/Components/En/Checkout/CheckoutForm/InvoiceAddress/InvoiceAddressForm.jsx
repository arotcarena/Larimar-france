import React, { useRef, useState } from 'react';
import * as yup from "yup";
import { useFormWithValidation } from '../../../../../CustomHook/form/useFormWithValidation';
import { TextField } from '../../../../../UI/Form/TextField';
import { FormButton } from '../../../../../UI/Form/FormButton';
import { AddressSuggestItem } from '../../../../../UI/AddressSuggestItem';
import { TextFieldWithSuggest } from '../../../../../UI/Form/TextFieldWithSuggest';
import { useMapSearch } from '../../../../../CustomHook/fetch/useMapSearch';
import { useCountrySearch } from '../../../../../CustomHook/fetch/useCountrySearch';
import { CountrySuggestItem } from '../../../../../UI/CountrySuggestItem';



const invoiceAddressSchema = yup.object({
    lineOne: yup.string().max(200, '200 characters max.').required('Street address is required'),
    lineTwo: yup.string().max(200, '200 characters max.'),
    postcode: yup.string().max(20, '20 characters max.').required('Postal code is required'),
    city: yup.string().max(200, '200 characters max.').required('City is required'),
    enCountry: yup.string().max(200, '200 characters max.').required('Country is required'),
}).required();


const createDefault = (address, defaultAddress, civilState) => {
    if( //si le nom de l'adresse de livraison est le même que le nom du compte
        defaultAddress.firstName.toLowerCase() === civilState.firstName.toLowerCase() && defaultAddress.lastName.toLowerCase() === civilState.lastName.toLowerCase()
        &&
        //et si tous les champs d'adresse sont pour l'instant vides
        address.lineOne === '' && address.lineTwo === '' && address.postcode === '' && address.city === '' && address.country === ''
    ) {
        //alors on insère l'adresse de livraison par défaut
        for(const [key, value] of Object.entries(address)) {
            address[key] = defaultAddress[key];
        }
    }
    return address;
}



export const InvoiceAddressForm = ({invoiceAddress, defaultAddress, civilState, setCheckoutData, forwardStep}) => {

     const { handleSubmit, errors, setValue, control } = useFormWithValidation(invoiceAddressSchema, createDefault(invoiceAddress, defaultAddress, civilState));
     const [isLoading, setLoading] = useState(false);
     const onSubmit = formData => {
         if(isLoading) {
             return;
         }
         if(!formData.iso) {
             setError('enCountry', {type: 'custom', message: 'Please choose a country among those proposed'});
             return;
         }
         setLoading(true);
         setCheckoutData(checkoutData => ({
             ...checkoutData,
             invoiceAddress: formData
         }));
         forwardStep();
         setLoading(false);
     };
 
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
        <form ref={formRef} onSubmit={handleSubmit(onSubmit)}>

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
                render={(country, isSelected, onSelect) => <CountrySuggestItem key={country.id} country={country} selected={isSelected} onSelect={onSelect} lang="en" />}
                >
                Country *
            </TextFieldWithSuggest>

            <FormButton loading={isLoading}>Submit</FormButton>
        </form>
    )
}