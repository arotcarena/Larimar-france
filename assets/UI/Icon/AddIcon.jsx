import React from 'react';

export const AddIcon = ({additionalClass = '', ...props}) => {
    return (
        <svg className={`icon add-icon ${additionalClass}`} xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960">
            <path fill="currentColor" d="M433-183v-250H183v-94h250v-250h94v250h250v94H527v250h-94Z"/>
        </svg>
    )
}


