import React from 'react';

export const EditIcon = ({additionalClass = '', lang = 'fr', ...props}) => {
    const alt = lang !== 'fr' ? 'edit logo': 'logo éditer';

    return <img className={`icon edit-icon ${additionalClass}`} src="/img/icons/edit.png" alt={alt} />
}