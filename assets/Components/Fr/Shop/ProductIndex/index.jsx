import React, { useEffect, useState } from 'react';
import { ProductCard } from './ProductCard';
import { useFetchWithDelay } from '../../../../CustomHook/fetch/useFetchWithDelay';
import '../../../../styles/Shop/ProductIndex/index.css';
import { Loader } from '../../../../UI/Icon/Loader';
import { Filters } from './Filters';
import { PaginationControls } from '../../../../UI/Pagination/PaginationControls';

export const ProductIndex = ({categoryId, subCategoryId}) => {

    const [filters, setFilters] = useState({
        categoryId: categoryId,
        subCategoryId: subCategoryId,
        q: '',
        material: '',
        page: 1,
        limit: 20
    });

    //on récupère le param q de l'url s'il est présent
    useEffect(() => {
        const parts = window.location.search.substring(1).split('&');
        const data = {};
        for(const part of parts) {
            const key = part.split('=')[0];
            const value = part.split('=')[1];
            if(key === 'q') {
                data[key] = decodeURIComponent(value).replaceAll('+', ' ');
            }
        }
        setFilters({
            ...filters, ...data
        });

        //puis on vide l'url de ses params
        history.pushState(null, null, window.location.pathname);
    }, []);
    
    //pagination
    const handlePageChange = newPage => {
        setFilters({
            ...filters,
            page: newPage
        });
    }


    const [result, loading, error] = useFetchWithDelay('/fr/api/product/index', filters, 'GET', 300);


    //scroll tout en haut à chaque modif des résultats (ex: changement de page)
    useEffect(() => {
        window.scroll(0, 0);
    }, [result]);

    return (
        <div className="product-index">
            <div className="product-index-header">
                <Filters filters={filters} setFilters={setFilters} /> 
            </div>
            {
                result && (
                    <div className={'product-index-results' + (loading ? ' loading': '')}>
                        {
                            loading && <Loader additionalClass="main-loader" />
                        }
                        <p className="product-index-count">{result.count} résultat{result.count > 1 ? 's': ''}</p>
                        <ul className="product-list">
                        {
                            result.products.map(product => <ProductCard key={product.id} product={product} />)
                        }
                        </ul>
                        {
                            result.maxPage > 1 && (
                                <PaginationControls currentPage={result.currentPage} maxPage={result.maxPage} pageChange={handlePageChange} />
                            )
                        }
                    </div>
                )
            }
        </div>
    )
}






