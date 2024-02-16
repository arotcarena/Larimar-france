import React from 'react';
import { HeaderTop } from './HeaderTop';
import { HeaderBottom } from './HeaderBottom';
import '../../../styles/Header/index.css';
import { HeaderLogoDesktop } from '../../../UI/Logo/HeaderLogoDesktop';
import categories from '../../../Config/categories.json';


export const Header = ({activeCategory, activeSubCategory}) => {

    return (
        <>
            <HeaderLogoDesktop />
            <div className="header-wrapper">
                <HeaderBottom categories={categories} activeCategory={activeCategory} activeSubCategory={activeSubCategory} />
                <div className="menu-separator"></div>
                <HeaderTop categories={categories} />
            </div>
        </>
    )
};




