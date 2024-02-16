import React from 'react';
import { Loader } from '../Icon/Loader';

export const FormButton = ({children, additionalClass, lang = 'fr', loading, ...props}) => {


    return (
        <button type="submit" className={'form-button' + (additionalClass ? ' '+additionalClass: '') + (loading ? ' disabled': '') } disabled={loading} {...props}>
        {
            loading 
            ? 
            <Loader lang={lang} />
            :
            <span>{children}</span>
        }
        </button>
    )
}
