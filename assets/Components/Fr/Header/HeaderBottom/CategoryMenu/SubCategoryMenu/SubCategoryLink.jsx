import React from 'react';

export const SubCategoryLink = ({subCategory, active}) => {
    return (
        <a className={'subcategory-link' + (active ? ' active': '')} href={subCategory.target}>
            {subCategory.name.split('_').join(' ')}
        </a>
    )
}