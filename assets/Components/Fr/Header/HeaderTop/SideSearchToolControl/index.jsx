import React, { useEffect } from 'react';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { Modal } from '../../../../../UI/Container/Modal';
import { SearchButton } from '../../../../../UI/Button/SearchButton';
import { SideSearchTool } from './SideSearchTool';

export const SideSearchToolControl = () => {

    const [searchToolIsOpen, openSearchTool, closeSearchTool] = useOpenState();

    

    return (
        <>
            <SearchButton additionalClass="header-search-link left-search-link" onClick={openSearchTool} />
            <Modal isOpen={searchToolIsOpen} close={closeSearchTool} additionalClass="left side-menu">
                <SideSearchTool />
            </Modal>
        </>
    )
}

