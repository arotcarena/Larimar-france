import React from 'react';
import '../../../../styles/Shop/ProductIndex/productCard.css';
import { useOpenState } from '../../../../CustomHook/useOpenState';
import { useControlledFetch } from '../../../../CustomHook/fetch/useControlledFetch';
import { cartChipAdd } from '../../../../functions/dom';
import { FormButton } from '../../../../UI/Form/FormButton';
import { TextConfig } from '../../../../Config/TextConfig';
import { useTouchingListener } from '../../../../CustomHook/listeners/useTouchingListener';



const quantityToAdd = 1;


export const ProductCard = ({product}) => {

    const [cartButtonIsOpen, openCartButton, closeCartButton] = useOpenState();

    const [addFetch, _, loading, error] = useControlledFetch(2000);

    const handleCartAdd = async e => {
        e.preventDefault();
        try {
            await addFetch('/fr/api/cart/add/id-'+product.id+'_quantity-'+quantityToAdd);
            cartChipAdd(quantityToAdd, product.price);
        }
        catch(e) {
            //
        } 
    };

    const isTouching = useTouchingListener();
 

    return (
        <li className="product-card" onMouseOver={openCartButton} onMouseLeave={closeCartButton}>
            <a className="product-card-img-link" href={product.target}>
                <img className="product-card-img" src={product.firstPicture.path} alt={product.firstPicture.alt} />
            </a>
            <div className="product-card-body">
                <h2 className="product-card-title"><a href={product.target}>{product.designation}</a></h2>
                {
                    product.material && (
                        <p className="product-card-text muted">{TextConfig.PRODUCT_MATERIALS[product.material]}</p>
                    )
                }
                <p className="product-card-text">{product.formatedPrice}</p>
                {/* {
                    product.categoryName && (
                        <p className="product-card-sub-text">
                            {product.categoryName} {product.subCategoryName ? (' / '+product.subCategoryName): ''}
                        </p>
                    )
                } */}
            </div>
            <div className="product-card-footer">
                <FormButton loading={loading} onClick={handleCartAdd} additionalClass={'product-card-button' + (cartButtonIsOpen || isTouching ? ' open': '')}>Ajouter au panier</FormButton>
            </div>
            {
                error && <div className="form-error">{error}</div>
            }
        </li>
    )
}