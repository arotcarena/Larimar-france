import React from 'react';
import { SubCategoryMenu } from './SubCategoryMenu/SubCategoryMenu';

export const CategoryLink = ({category, setSelected, selected, active, activeSubCategory}) => {

    const handleClick = () => {
        if(selected) {
            setSelected(null)
        } else {
            setSelected(category);
        }
    }

    const handleOpenExpand = () => {
        setSelected(category);
    };
    const handleKeyDown = e => {
        if(e.key === 'Enter') {
            if(!selected) {
                e.preventDefault();
                e.stopPropagation();
                handleOpenExpand();
            }
            
        }
    };
    const handleCloseExpand = (e) => {
        setSelected(null);
    };
    return (
        <div role="button" tabIndex="0" className={'header-bottom-link' + (active ? ' active': '')} 
            onMouseOver={handleOpenExpand} onClick={handleClick} onKeyDown={handleKeyDown} onMouseLeave={handleCloseExpand}
            >
            { category.name.split('_').join(' ') }
            
            {
                selected && category.subCategories.length > 0 && (
                    <div className="header-bottom-expand">
                        <SubCategoryMenu subCategories={category.subCategories} activeSubCategory={activeSubCategory} />
                    </div>
                )
            }
        </div>
    )
}