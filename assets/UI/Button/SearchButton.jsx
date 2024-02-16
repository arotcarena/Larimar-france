import React from 'react';
import { SearchIcon } from '../Icon/SearchIcon';
import { Button } from './Button';

export const SearchButton = ({onClick, additionalClass, lang='fr', ...props}) => {
    const ariaLabel = lang === 'en' ? 'Search': 'Rechercher';
    return (
        <Button additionalClass={`icon-button ${additionalClass}`} aria-label={ariaLabel} onClick={onClick} {...props}> 
            <SearchIcon />
        </Button>
    )
}