import React, { useEffect, useState } from 'react';
import { Loader } from '../../../../UI/Icon/Loader';
import { Purchase } from './Purchase';
import '../../../../styles/Account/purchase.css';
import { PaginationControls } from '../../../../UI/Pagination/PaginationControls';

export const AccountPurchase = ({manager: [purchaseFetch, data, purchaseIsLoading, purchaseErrors]}) => {

    useEffect(() => {
        if(!data) {
            purchaseFetch('/en/api/purchase/findPaginatedLight?page=1');
        }
    }, []);

    const handlePageChange = newPage => {
        purchaseFetch('/en/api/purchase/findPaginatedLight?page='+newPage);
    }

    if(purchaseIsLoading && !data) {
        return <Loader />
    } else if(!data) {
        return <div>Unable to show your orders. Please try again later.</div>
    } else if(data.count === 0) {
        return <div>You have no order for the moment.</div>
    }
    return (
        <div className="account-purchase">
            <div className="purchase-count">{data.count} order{data.count > 1 ? 's': ''}</div>
            <ul className={'purchase-list' + (purchaseIsLoading ? ' loading': '')}>
                {
                    data.purchases.map(purchase => <Purchase key={purchase.id} purchase={purchase} />)
                }
                { purchaseIsLoading && <Loader additionalClass="main-loader" />}
            </ul>
            <PaginationControls currentPage={data.currentPage} maxPage={data.maxPage} pageChange={handlePageChange} isLoading={purchaseIsLoading} lang="fr" />
        </div>
    )
}