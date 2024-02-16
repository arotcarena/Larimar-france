import React, { useState } from 'react';
import { useFetchQSearch } from '../../../../../../CustomHook/fetch/useFetchQSearch';
import { ProductSuggest } from './ProductSuggest';
import '../../../../../../styles/header/HeaderTop/sideSearchTool.css';
import { SearchIcon } from '../../../../../../UI/Icon/SearchIcon';
import { Loader } from '../../../../../../UI/Icon/Loader';
import { useButtonLoading } from '../../../../../../CustomHook/form/useButtonLoading';

export const SideSearchTool = () => {

    //loading du bouton "voir tous les résultats"
    const [buttonLoading, handleButtonClick] = useButtonLoading();

    //handle q change
    const [q, setQ] = useState('');
    const handleChange = e => {
        setQ(e.currentTarget.value);
    }
    
    //fetch products & count
    const [result, resetProducts, loading, error] = useFetchQSearch('/en/api/product/search', q);
 
    return (
        <div className="side-search-tool">
            <form className="q-group" action="/en/recherche" method="GET">
                <input name="q" className="q-input" type="text" placeholder="Search" value={q} onChange={handleChange} autoFocus={true} />
                <SearchIcon additionalClass="q-icon" />
            </form>
            <div className={'product-suggest-list' + (loading ? ' loading': '')}>
                {
                    loading && <Loader lang="en" />
                }
                {
                    result && (
                        result.products.map(product => <ProductSuggest key={product.id} product={product} />)
                    )
                }
            </div>
            <div className="search-link">
                <a className={'side-menu-footer-button' + (buttonLoading ? ' disabled': '')} onClick={handleButtonClick} href={'/en/search?q='+q}>
                    {
                        buttonLoading
                        ?
                        <Loader lang="en" additionalClass="form-button-loader" />
                        :
                        <span>See all results {result?.count ? `(${result.count})`: ''}</span>
                    }
                </a>
            </div>
        </div>
    )

}