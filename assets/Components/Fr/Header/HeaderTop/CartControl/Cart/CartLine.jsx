import React, { memo, useEffect, useRef, useState } from 'react';
import { Button } from '../../../../../../UI/Button/Button';
import { CloseIcon } from '../../../../../../UI/Icon/CloseIcon';
import { priceFormater } from '../../../../../../functions/formaters';
import { TextConfig } from '../../../../../../Config/TextConfig';

export const CartLine = memo(({product, quantity, totalPrice, error, remove, add, less}) => {

    // const handleAdd = e => {
    //     e.preventDefault();
    //     add(product.id, 1, quantity + 1);
    //     //obligatoire pour affichage temporaire de l'erreur
    //     renderError();
    // }
    // const handleLess = e => {
    //     e.preventDefault();
    //     less(product.id, 1, quantity - 1);
    //     //obligatoire pour affichage temporaire de l'erreur
    //     renderError();
    // }

    // //obligatoire pour l'affichage temporaire de l'erreur
    // const [errorMessage, setErrorMessage] = useState(null);
    // useEffect(() => {
    //     renderError();
    // }, [error]);

    // const renderError = () => {
    //     setErrorMessage(error);
    //     setTimeout(() => {
    //         setErrorMessage(null);
    //     }, 2000);
    // }



    
    //pour mettre un espace sous la ligne au moment du cart removing
    const cartLineRef = useRef(null);
    //confirmation on cart item remove
    const [removing, setRemoving] = useState(false);
    const handleRemoveClick = () => {
        setRemoving(true);
        cartLineRef.current.classList.add('removing');
    }
    const handleConfirmRemove = () => {
        remove(product.id);
    }
    const handleCancelRemove = () => {
        setRemoving(false);
        cartLineRef.current.classList.remove('removing');
    }
    

    return (
            <li className="cart-line-wrapper" ref={cartLineRef}>
                <div className="cart-line">
                    <a className="cart-line-img-link" href={product.target}>
                        <img className="cart-line-img" src={product.firstPicture.path} alt={product.firstPicture.alt} />
                    </a>
                    <div className="cart-line-body">
                        <h3 className="cart-line-title"><a href={product.target}>{product.designation}</a></h3>
                        {
                            product.material && (
                                <p className="cart-line-text muted">{TextConfig.PRODUCT_MATERIALS[product.material]}</p>
                            )
                        }
                        <p className="cart-line-text cart-line-price">{product.formatedPrice}</p>
                        {/* <p className="cart-line-text">
                            Quantité : 
                            <button className="cart-line-minus" onClick={handleLess}>-</button>
                            {quantity}
                            <button className="cart-line-plus" onClick={handleAdd}>+</button>
                        </p>
                        {
                            errorMessage && (
                                <div className="form-error">{errorMessage}</div>
                            )
                        }
                        <p className="cart-line-text">
                            Sous-total : {priceFormater(totalPrice)}
                        </p> */}
                    </div>
                    <Button aria-label="Supprimer" additionalClass="icon-button cart-line-remover" onClick={handleRemoveClick} title="Supprimer">
                        <CloseIcon />
                    </Button>
                </div>
                {
                    removing && (
                        <div className="confirm-remove-wrapper">
                            <div className="confirm-remove">
                                <span>Supprimer ?</span>
                                <div className="confirm-remove-controls">
                                    <button className="confirm-remove-button yes" onClick={handleConfirmRemove}>Oui</button>
                                    <button className="confirm-remove-button no" onClick={handleCancelRemove}>Non</button>
                                </div>
                            </div>
                        </div>
                    )
                        
                }
            </li>
    )
});