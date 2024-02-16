import React from 'react';
import { CategoryMenu } from './CategoryMenu';
import '../../../../styles/Header/HeaderBottom/index.css';

export const HeaderBottom = ({categories, activeCategory, activeSubCategory}) => {

    return (
        <div className="header-bottom">
            <CategoryMenu categories={categories} activeCategory={activeCategory} activeSubCategory={activeSubCategory} />
        </div>
    )
}

