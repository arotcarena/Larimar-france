import React, { useEffect, useState } from 'react';
import { Loader } from '../../../../UI/Icon/Loader';
import { Purchase } from './Purchase';
import '../../../../styles/Account/purchase.css';
import { PaginationControls } from '../../../../UI/Pagination/PaginationControls';

export const AccountPurchase = ({manager: [purchaseFetch, data, purchaseIsLoading, purchaseErrors]}) => {

    useEffect(() => {
        if(!data) {
            purchaseFetch('/fr/api/purchase/findPaginatedLight?page=1');
        }
    }, []);

    const handlePageChange = newPage => {
        purchaseFetch('/fr/api/purchase/findPaginatedLight?page='+newPage);
    }

    if(purchaseIsLoading && !data) {
        return <Loader />
    } else if(!data) {
        return <div>Impossible de charger vos commandes. Veuillez réessayer ultérieurement.</div>
    } else if(data.count === 0) {
        return <div>Vous n'avez aucune commande pour l'instant.</div>
    }
    return (
        <div className="account-purchase">
            <div className="purchase-count">{data.count} commande{data.count > 1 ? 's': ''}</div>
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