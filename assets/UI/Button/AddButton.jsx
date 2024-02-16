import React from 'react';
import { AddIcon } from '../Icon/AddIcon';

export const AddButton = ({additionalClass = '', lang = 'fr', ...props}) => {

    const ariaLabel = lang !== 'fr' ? 'Add': 'Ajouter';

    return (
        <button className={`add-button ${additionalClass}`} {...props} aria-label={ariaLabel}>
            <AddIcon />
        </button>
    )
}
