import React, { useState } from 'react';
import { Loader } from '../Icon/Loader';

export const LinkButton = ({children, href, additionalClass}) => {
    
    const [isLoading, setLoading] = useState(false);
    const handleClick = e => {
        if(isLoading) {
            e.preventDefault();
            return;
        }
        setLoading(true);
    }
    
    return (
        <a href={href} onClick={handleClick} className={'form-button' + (additionalClass ? ' '+additionalClass: '') + (isLoading ? ' disabled': '')}>
            {
                isLoading ? <Loader /> : <span>{children}</span>
            }
        </a>
    )
}