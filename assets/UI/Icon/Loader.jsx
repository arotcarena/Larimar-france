import React from 'react';

export const Loader = ({additionalClass = '', lang = 'fr', ...props}) => {

    const alt = lang !== 'fr' ? 'Loading symbol': 'Symbole de chargement';

    return <img className={`loader ${additionalClass}`} src="/img/icons/loader.png" alt={alt} {...props} />
};
