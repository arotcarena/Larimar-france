import React from 'react';

export const WhiteLoader = ({additionalClass, lang = 'fr', ...props}) => {

    const alt = lang !== 'fr' ? 'Loading symbol': 'Symbole de chargement';

    return <img className={`loader ${additionalClass}`} src="/img/icons/white_loader.png" alt={alt} {...props} />
};
