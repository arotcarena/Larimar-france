import React from 'react';
import { CheckIcon } from '../Icon/CheckIcon';
import { Button } from './Button';

export const CheckButton = ({additionalClass = '', lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'validate': 'valider';

    return (
        <Button additionalClass={`icon-button check-button ${additionalClass}`} aria-label={ariaLabel} {...props}> 
            <CheckIcon />
        </Button>
    )
}
