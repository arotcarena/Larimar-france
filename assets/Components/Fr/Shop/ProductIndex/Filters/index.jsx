import React, { useEffect, useRef } from 'react';
import '../../../../../styles/Shop/ProductIndex/filters.css';
import { FilterIcon } from '../../../../../UI/Icon/FilterIcon';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { CloseButton } from '../../../../../UI/Button/CloseButton';
import { SearchIcon } from '../../../../../UI/Icon/SearchIcon';
import { AddFilters } from './AddFilters';

export const Filters = ({filters, setFilters}) => {

    const [addFiltersIsOpen, openAddFilters, closeAddFilters] = useOpenState(false);

    const handleChange = e => {
        setFilters(filters => ({
            ...filters,
            [e.target.name]: e.target.value
        }));
    };

    return (
        <div className="search-filters">
            {
                filters.q !== '' && <h1>Résultats pour "{filters.q}"</h1>
            }
            <div className="searchbar-wrapper">
                <input type="text" className="searchbar" name="q" value={filters.q} onChange={handleChange} placeholder="Rechercher" />
                <SearchIcon />
            </div>
            <div className="add-filters-wrapper mobile">
                {
                    !addFiltersIsOpen && (
                        <button onClick={openAddFilters}>
                            <FilterIcon />
                        </button>
                    )
                }
                {
                    addFiltersIsOpen && (
                        <CloseButton onClick={closeAddFilters} />
                    )
                }
                {
                    addFiltersIsOpen && (
                        <AddFilters setFilters={setFilters} />
                    )
                }
            </div>
            <div className="add-filters-wrapper desktop">
                <AddFilters setFilters={setFilters} alwaysOpen={true} />
            </div>
        </div>
    )
}