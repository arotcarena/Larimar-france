import React, { useEffect } from 'react';
import { CartLine } from './CartLine';
import { priceFormater } from '../../../../../../functions/formaters';
import { Loader } from '../../../../../../UI/Icon/Loader';
import { useButtonLoading } from '../../../../../../CustomHook/form/useButtonLoading';

export const Cart = ({cart, fetchCart, remove, add, less}) => {

    const [buttonLoading, handleButtonClick] = useButtonLoading();

    useEffect(() => {
        fetchCart();
    }, []);

    return (
        <div className="cart-modal">
            <div className="cart-header side-menu-header">
                <h2 className="cart-title">
                    Cart ({cart.count ?? '0'})
                </h2>
            </div>
            {
                cart.generalLoading && (
                    <div className="cart-sub-header">
                        <Loader lang="en" />
                    </div>
                )
            }
            
            {
                !cart.generalLoading && cart.lines.length === 0 && <div className="cart-sub-header">The cart is empty</div>
            }

            {
                cart.lines.length > 0 &&
                (
                    <>
                        <div className="cart-body">
                            <ul className={'cart-list' + (cart.generalLoading ? ' loading': '')}>
                                {
                                    cart.lines.map((line) => (
                                        <CartLine 
                                            key={line.product.id} 
                                            product={line.product}
                                            quantity={line.quantity}
                                            totalPrice={line.totalPrice}
                                            error={line.error} 
                                            remove={remove} 
                                            add={add} 
                                            less={less} 
                                            />
                                    ))
                                }
                            </ul>
                        </div>
                        <div className="cart-footer">
                            <p className="cart-total">Total : {priceFormater(cart.totalPrice)}</p>
                            <a href="/en/checkout" className={'cart-footer-link side-menu-footer-button' + (buttonLoading ? ' disabled': '')} onClick={handleButtonClick}>
                                {
                                    buttonLoading 
                                    ?
                                    <Loader lang="en" />
                                    :
                                    <span>Proceed to payment</span>
                                }
                            </a>
                        </div>
                    </>
                )
            }
            
        </div>
    )
}