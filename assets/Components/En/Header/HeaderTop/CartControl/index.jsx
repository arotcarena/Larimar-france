import React, { useEffect } from 'react';
import { useOpenState } from '../../../../../CustomHook/useOpenState';
import { CartButton } from '../../../../../UI/Button/CartButton';
import { Modal } from '../../../../../UI/Container/Modal';
import { Cart } from './Cart';
import { cartChipUpdate } from '../../../../../functions/dom';
import { useCartFetch } from '../../../../../CustomHook/fetch/useCartFetch';
import '../../../../../styles/Header/HeaderTop/cart.css';

export const CartControl = () => {
    
    const [cartIsOpen, openCart, closeCart] = useOpenState(
        window.location.search.substring(1) === 'cart' ? true: false
    );
    
    const {cart, ...cartFetchRest} = useCartFetch('en');


    useEffect(() => {
        cartChipUpdate(cart.count, cart.totalPrice);
    }, [cart]);


    return (
        <>
            <CartButton onClick={openCart} additionalClass="cart-opener" lang="en">
                <div className="cart-chip" hidden={true}>
                    <div className="cart-count-chip"></div>
                    <div className="cart-price-chip"></div>
                </div>
            </CartButton>
            <Modal isOpen={cartIsOpen} close={closeCart} additionalClass="right side-menu">
                <Cart cart={cart} {...cartFetchRest} />
            </Modal>
        </>
    )
}