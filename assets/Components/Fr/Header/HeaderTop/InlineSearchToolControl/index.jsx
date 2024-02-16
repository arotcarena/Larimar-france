import React from 'react';
import { SearchButton } from '../../../../../UI/Button/SearchButton';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { InlineSearchTool } from './InlineSearchTool';


export const InlineSearchToolControl = () => {
    
    const [searchToolIsOpen, openSearchTool, closeSearchTool] = useOpenState();

    return (
            searchToolIsOpen 
            ?
            <InlineSearchTool close={closeSearchTool} />
            :
            <SearchButton additionalClass="header-search-link right-search-link" onClick={openSearchTool} />
    )
}

