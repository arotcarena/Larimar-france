import React from 'react';

export const HeaderLogo = ({lang = 'fr'}) => {
    
    const alt = lang !== 'fr' ? 'Larimar logo': 'Logo Larimar';
    const href = lang !== 'fr' ? '/en': '/';
    
    return (
        <a href={href}>
            <img className="header-logo" src="/img/logo.png" alt={alt} />
        </a>
    )
} 