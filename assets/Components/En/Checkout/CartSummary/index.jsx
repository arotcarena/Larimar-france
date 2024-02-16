import React from 'react';
import { CartSummaryLine } from './CartSummaryLine';
import { priceFormater } from '../../../../functions/formaters';
import { Loader } from '../../../../UI/Icon/Loader';

export const CartSummary = ({cart, loading, errors}) => {
    return (
        <div className="cart-summary">
            <h2>My order</h2>
            {
                loading && <div className="info-group no-ml"><Loader /></div>
            }
            {
                cart && (
                    <div className="cart-summary-body">
                        <ul>
                            {
                                cart.cartLines.map((cartLine, index) => <CartSummaryLine key={index} cartLine={cartLine} />)
                            }
                        </ul>
                        <div className="cart-summary-footer">
                            <div className="cart-total-line">
                                <span>Total items</span>
                                <span className="totalPrice">{priceFormater(cart.totalPrice)}</span>
                            </div>
                            <div className="cart-total-line">
                                <span>Shipping cost</span>
                                <span className="shippingCost">{priceFormater(550)}</span>
                            </div>
                            <div className="cart-summary-separator"></div>
                            <div className="cart-total-line main">
                                <span>Total</span>
                                <span className="totalPrice">{priceFormater(cart.totalPrice + 550)}</span>
                            </div>
                        </div>
                    </div>
                )
            }
        </div>
    )

}
