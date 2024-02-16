import React, { useState } from 'react';
import { ExpandMoreIcon } from '../../../../../UI/Icon/ExpandMoreIcon';
import { CheckboxFields } from '../../../../../UI/Form/CheckboxFields';
import { TextConfig } from '../../../../../Config/TextConfig';

export const AddFilters = ({setFilters, alwaysOpen = false}) => {

    const [isOpen, setOpen] = useState(false);
    const handleClick = e => {
        setOpen(isOpen => !isOpen)
    }

    const handleChange = (name, value) => {
        setFilters(filters => ({
            ...filters,
            [name]: value
        }));
    }

    return (
        <div className="add-filters">
            <div className="add-filter">
                <div className="add-filter-label-wrapper">
                    <div className="add-filter-label">Matière</div>
                    {
                        !alwaysOpen && (
                            <button onClick={handleClick}>
                                <ExpandMoreIcon additionalClass={isOpen ? 'expanded': ''} />    
                            </button>
                        )
                    }
                </div>
                {
                    (isOpen || alwaysOpen) && (
                        <div className="add-filter-controls">
                            <CheckboxFields 
                                name="material"
                                labelValues={TextConfig.PRODUCT_MATERIALS_LABEL_VALUES}
                                onChange={handleChange}
                            />
                        </div>
                    )
                }
            </div>
            
        </div>
    )
}