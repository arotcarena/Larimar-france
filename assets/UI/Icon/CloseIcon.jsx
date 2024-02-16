import React from 'react';
import { Icon } from './Icon';

export const CloseIcon = ({additionalClass, ...props}) => {
    return (
        <svg className={'i-close' + (additionalClass ? ' ' + additionalClass: '')} {...props} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
            <path fill="currentColor" d="m4.59 59.41a2 2 0 0 0 2.83 0l24.58-24.58 24.59 24.58a2 2 0 0 0 2.83-2.83l-24.59-24.58 24.58-24.59a2 2 0 0 0 -2.83-2.83l-24.58 24.59-24.59-24.58a2 2 0 0 0 -2.82 2.82l24.58 24.59-24.58 24.59a2 2 0 0 0 0 2.82z"></path>
        </svg>
    )
}
