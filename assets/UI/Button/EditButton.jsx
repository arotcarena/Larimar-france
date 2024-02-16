import React from 'react';
import { EditIcon } from '../Icon/EditIcon';
import { Button } from './Button';

export const EditButton = ({additionalClass = '', lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'edit': 'éditer';

    return (
        <Button additionalClass={`icon-button edit-button ${additionalClass}`} aria-label={ariaLabel} {...props}> 
            <EditIcon />
        </Button>
    )
}
