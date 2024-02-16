import React from 'react';
import { HeaderLogo } from '../../../../UI/Logo/HeaderLogo';
import { CartControl } from './CartControl';
import { MobileMenuControl } from './MobileMenuControl';
import '../../../../styles/Header/HeaderTop/index.css';
import { SideSearchToolControl } from './SideSearchToolControl';
import { InlineSearchToolControl } from './InlineSearchToolControl';
import { AccountControl } from './AccountControl';

export const HeaderTop = ({categories}) => {


    return (
        <div className="header-top">
            <div>
                <MobileMenuControl categories={categories} />
                <SideSearchToolControl />
            </div>
            
            <HeaderLogo />

            <div className="header-top-right">
                <InlineSearchToolControl />
                <AccountControl />
                <CartControl />
            </div>
        </div>
    )
}