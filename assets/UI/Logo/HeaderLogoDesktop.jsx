import React from 'react';

export const HeaderLogoDesktop = ({lang = 'fr'}) => {
    
    const alt = lang !== 'fr' ? 'Larimar logo': 'Logo Larimar';
    const href = lang !== 'fr' ? '/en': '/';

    return (
        <a className="header-logo-desktop-wrapper" href={href}>
            <img className="header-logo-desktop" src="/img/logo.png" alt={alt} />
        </a>
    )
} 