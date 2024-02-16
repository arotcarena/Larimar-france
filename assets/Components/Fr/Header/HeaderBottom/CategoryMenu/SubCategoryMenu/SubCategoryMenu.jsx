import React from 'react';
import { SubCategoryLink } from './SubCategoryLink';

export const SubCategoryMenu = ({subCategories, activeSubCategory}) => {

    return (
        <nav className="header-bottom-subnav">
        {
            subCategories.map(
                subCategory => <SubCategoryLink key={subCategory.id} subCategory={subCategory} active={subCategory.id ===  parseInt(activeSubCategory)} />
            )
        }
        </nav>
    )
}