import React from 'react';

export const ProductSuggest = ({product}) => {

    let categoryFullName = null;
    if(product.fullName.split('dans').length === 2) {
        categoryFullName = product.fullName.split('dans')[1];
    } 

    return (
        <a className="product-suggest-item" href={product.target}>
            <img className="item-img" src={product.firstPicture.path} alt={product.firstPicture.alt}/>
            <div className="item-text">
                <h2 className="item-title">{product.designation}</h2>
                {
                    categoryFullName && <p className="item-category">{categoryFullName}</p>
                }
                <p className="item-price">{product.formatedPrice}</p>
            </div>
        </a>
    )
}

