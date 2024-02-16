import React from 'react';

export const CountrySuggestItem = ({country, selected, onSelect, lang = 'fr'}) => {

    const handleClick = e => {
        e.preventDefault();
        onSelect(country);
    }

    const countryName = lang !== 'fr' ? country.enName: country.name;

    return (
        <li className={'address-suggest-item suggest-item' + (selected ? ' selected': '')} onClick={handleClick}>
            {countryName}
        </li>
    )
}