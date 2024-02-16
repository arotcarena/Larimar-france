import React from 'react';

export const MenuIcon = ({additionalClass, ...props}) => {
    return (
        <svg  className={'icon i-menu' + (additionalClass ? ' ' + additionalClass: '')} {...props} enableBackground="new 0 0 32 32" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
          <g>
            <path fill="currentColor" d="m25.3 9h-18.6c-.4 0-.7-.3-.7-.8s.3-.8.8-.8h18.5c.4 0 .8.3.8.8s-.4.8-.8.8z"></path>
          </g>
          <g>
            <path fill="currentColor" d="m25.3 16h-18.6c-.4 0-.7-.3-.7-.8s.3-.8.8-.8h18.5c.4 0 .8.3.8.8s-.4.8-.8.8z"></path>
          </g>
          <g>
            <path fill="currentColor" d="m25.3 23h-18.6c-.4 0-.7-.3-.7-.8s.3-.8.8-.8h18.5c.4 0 .8.3.8.8s-.4.8-.8.8z"></path>
          </g>
        </svg>
    )
}