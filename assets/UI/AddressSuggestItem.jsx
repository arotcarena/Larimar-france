import React from 'react';

export const AddressSuggestItem = ({address, selected, onSelect}) => {

    const handleClick = e => {
        e.preventDefault();
        onSelect(address);
    }

    const completeAddress = `${address.lineOne}, ${address.postcode} ${address.city}`;
    const excerpt = completeAddress.length <= 50 ? completeAddress : (completeAddress.substring(0, 47) + '...');

    return (
        <li className={'address-suggest-item suggest-item' + (selected ? ' selected': '')} onClick={handleClick}>
            {excerpt}
        </li>
    )
}