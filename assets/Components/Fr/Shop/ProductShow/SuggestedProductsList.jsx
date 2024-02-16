import React from 'react';
import { Loader } from '../../../../UI/Icon/Loader';
import { useFetch } from '../../../../CustomHook/fetch/useFetch';
import { ProductCard } from '../ProductIndex/ProductCard';
import '../../../../styles/Shop/ProductIndex/index.css';
import '../../../../styles/Shop/ProductShow/index.css';

export const SuggestedProductsList = ({productId}) => {

    const [suggestedProducts, loading, errors] = useFetch('/fr/api/product/'+ productId +'/getSuggestedProducts');


    if(loading) {
        return <Loader additionalClass="main-loader" />
    }
    if(suggestedProducts === null || suggestedProducts.length === 0) {
        return;
    }

    return (
        <>
            <h2 className="suggested-products-title">Suggestions</h2>
            <ul className="suggested-products-list product-list">
                {
                    suggestedProducts.map(suggestedProduct => <ProductCard key={suggestedProduct.id} product={suggestedProduct} />)
                }
            </ul>
        </>
    )
}

