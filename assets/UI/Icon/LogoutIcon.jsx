import React from 'react';

export const LogoutIcon = ({additionalClass, ...props}) => {
    return (
        <svg className={'icon i-logout' + (additionalClass ? ' ' + additionalClass: '')} {...props} enableBackground="new 0 0 500 500" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="m303.69 500h-264.38c-21.676 0-39.31-17.635-39.31-39.311v-421.378c0-21.676 17.634-39.311 39.31-39.311h264.38c21.675 0 39.31 17.635 39.31 39.311v75.689c0 5.523-4.479 10-10 10-5.522 0-10-4.477-10-10v-75.689c0-10.648-8.662-19.311-19.31-19.311h-264.38c-10.648 0-19.31 8.663-19.31 19.311v421.379c0 10.648 8.662 19.31 19.31 19.31h264.38c10.647 0 19.311-8.662 19.311-19.311v-81.689c0-5.521 4.478-10 10-10 5.521 0 10 4.479 10 10v81.689c-.001 21.676-17.636 39.311-39.311 39.311z"></path>
            <g>
                <path fill="currentColor" d="m141 250c0-5.522 4.479-10 10-10h320c5.523 0 10 4.478 10 10s-4.477 10-10 10h-320c-5.521 0-10-4.478-10-10z"></path>
                <g>
                    <path fill="currentColor" d="m482.93 256.644-84.146-84.146c-3.905-3.904-3.905-10.235 0-14.142s10.237-3.905 14.142 0l84.146 84.146c3.905 3.905 3.905 10.237 0 14.143-1.952 1.952-4.512 2.929-7.071 2.929-2.559-.001-5.12-.977-7.071-2.93z"></path>
                    <path fill="currentColor" d="m398.783 341.643c-3.905-3.906-3.905-10.238 0-14.144l84.146-84.145c3.905-3.904 10.237-3.904 14.143 0 3.905 3.906 3.905 10.238 0 14.143l-84.146 84.146c-1.951 1.952-4.512 2.929-7.07 2.929-2.56 0-5.118-.976-7.073-2.929z"></path>
                </g>
            </g>
        </svg>
    )
}