import React, { useEffect, useRef, useState } from 'react';
import { useFetchQSearch } from '../../../../../../CustomHook/fetch/useFetchQSearch';
import { ProductSuggest } from './ProductSuggest';
import '../../../../../../styles/header/HeaderTop/inlineSearchTool.css';
import { SearchIcon } from '../../../../../../UI/Icon/SearchIcon';
import { CloseButton } from '../../../../../../UI/Button/CloseButton';
import { WhiteLoader } from '../../../../../../UI/Icon/WhiteLoader';

export const InlineSearchTool = ({close}) => {

    const inputRef = useRef(null);

    //handle q change
    const [q, setQ] = useState('');
    const handleChange = e => {
        setQ(e.currentTarget.value);
    }
    
    //fetch products & count
    const [result, resetProducts, loading, error] = useFetchQSearch('/fr/api/product/search', q);

    const handleBodyClick = e => {
        resetProducts();
        setQ('');
        inputRef.current.focus();
    }
    
    useEffect(() => {
        document.body.addEventListener('click', handleBodyClick);
        return () => document.body.removeEventListener('click', handleBodyClick);
    }, []);

    return (
        <div className="inline-search-tool" onClick={e => e.stopPropagation()}>
            <div className="search-tool-header">
                <form className="q-group" action="/fr/recherche" method="GET">
                    <input ref={inputRef} name="q" className="q-input" type="text" placeholder="Rechercher" value={q} onChange={handleChange} autoFocus={true} />
                    <SearchIcon additionalClass="q-icon" />
                </form>
                <CloseButton onClick={close} />
            </div>
            {
                (result?.products.length > 0 || loading) && (
                    <div className="inline-search-tool-expand-wrapper">
                        <div className="inline-search-tool-expand">
                            {
                                loading && <WhiteLoader />
                            }
                            <div className="product-suggest-list">
                                {
                                    result?.products.length > 0 && (
                                        result.products.map(product => <ProductSuggest key={product.id} product={product} />)
                                    )
                                }
                            </div>
                            <div className="search-link">
                                <a href={'/fr/recherche?q='+q}>Voir tous les résultats {result?.count ? `(${result.count})`: ''}</a>
                            </div>
                        </div>
                    </div>
                )
            }
        </div>
    )
}