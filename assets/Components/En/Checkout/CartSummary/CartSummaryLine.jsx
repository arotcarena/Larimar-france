import React from 'react';
import { priceFormater } from '../../../../functions/formaters';

export const CartSummaryLine = ({cartLine}) => {
    return (
        <li className="cart-summary-line">
            <a className="cart-summary-line-img-block" href={cartLine.product.target}>
                <img className="cart-summary-line-img" src={cartLine.product.firstPicture.path} alt={cartLine.product.firstPicture.alt} />
            </a>
            <div className="cart-summary-line-text">
                <p className="designation">
                    <a href={cartLine.product.target}>{cartLine.product.designation}</a> 
                </p>      
                <p className="price">{priceFormater(cartLine.product.price)}</p>
            </div>
            {/* <div>
                Quantité : {cartLine.quantity}
            </div>
            <div>
                Sous-total : {priceFormater(cartLine.totalPrice)}
            </div> */}
        </li>
    )
}