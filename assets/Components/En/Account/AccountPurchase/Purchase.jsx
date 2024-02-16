import React, { useState } from 'react';
import { apiFetch } from '../../../../functions/api';
import { Loader } from '../../../../UI/Icon/Loader';
import { ExpandMoreIcon } from '../../../../UI/Icon/ExpandMoreIcon';
import { priceFormater } from '../../../../functions/formaters';
import { translateCivility, translateStatus } from '../../../../functions/translaters';
import { SiteConfig } from '../../../../Config/SiteConfig';

export const Purchase = ({purchase}) => {

    //remplacer par useOpenState
    const [isExpanded, setExpanded] = useState(false); 

    const [fullPurchase, setFullPurchase] = useState(null);
    const [fullPurchaseIsLoading, setFullPurchaseIsLoading] = useState(false);


    const handleExpandClick = async e => {
        e.preventDefault();
        if(isExpanded) {
            setExpanded(false);
            return;
        }
        setExpanded(true);
        if(!fullPurchase) {
            setFullPurchaseIsLoading(true);
            try {
                const full = await apiFetch('/en/api/purchase/'+purchase.id+'/findOneFull');
                setFullPurchase(full);
            } catch(e) {
                //
            }
            setFullPurchaseIsLoading(false);
        }
    }

    return (
        <div className="purchase-item" onClick={handleExpandClick}>
            <div className="purchase-item-row purchase-item-header">
                <span className="purchase-light-item">Order n°{purchase.ref}</span>
                <span className="purchase-light-item">{priceFormater(purchase.total)}</span>
            </div>
            {
                isExpanded && (
                    <div className="purchase-item-body">
                        <FullPurchase fullPurchase={fullPurchase} isLoading={fullPurchaseIsLoading} />
                    </div>
                )
            }
            <div className="purchase-item-row purchase-item-footer">
                <span className="purchase-light-item">{purchase.createdAt}</span>
                <span className="purchase-light-item">{translateStatus(purchase.status)}</span>
            </div>
            <button className="expand-button" onClick={handleExpandClick}>
                {isExpanded ? <ExpandMoreIcon additionalClass="expanded" />: <ExpandMoreIcon />}
            </button>
        </div>
    )
}

const FullPurchase = ({fullPurchase, isLoading, close}) => {
    if(isLoading) {
        return <Loader />
    } else if(!fullPurchase) {
        return <div>Unable to show full order, please try again later</div>
    }
    const {deliveryDetail, invoiceDetail, purchaseLines, tracking, shippingInfo} = fullPurchase;
    return (
        <>
            <div className="purchase-separator"></div>
            <div className="purchase-full-item">
                
                <div className="purchase-full-item-title">Mode de livraison</div>
                <p className="uppercase">{shippingInfo.name}</p>
                <div className="purchase-separator"></div>
                {
                    shippingInfo.mode === SiteConfig.DELIVERY_MODE_RELAY && shippingInfo.relay !== null
                    ?
                    <>
                        <div className="purchase-full-item-title">Delivery to a pickup point / locker</div>
                        <p className="uppercase">{shippingInfo.relay.name}</p>
                        <p className="uppercase">{shippingInfo.relay.lineOne}</p>
                        {
                            shippingInfo.relay.lineTwo && <p className="uppercase">{shippingInfo.relay.lineTwo}</p>
                        }
                        <p className="uppercase">{shippingInfo.relay.postcode} {shippingInfo.relay.city}</p>
                        <p className="uppercase">{deliveryDetail.country}</p>
                    </>
                    :
                    <>
                        <div className="purchase-full-item-title">Delivery address</div>
                        <p className="capitalize">{translateCivility(deliveryDetail.civility)} {deliveryDetail.firstName} {deliveryDetail.lastName}</p>
                        <p>{deliveryDetail.lineOne}</p>
                        {
                            deliveryDetail.lineTwo && <p>{deliveryDetail.lineTwo}</p>
                        }
                        <p>{deliveryDetail.postcode} {deliveryDetail.city}</p>
                        <p>{deliveryDetail.country}</p>
                    </>
                }
            </div>
            <div className="purchase-separator"></div>
            <div className="purchase-full-item">
                <div className="purchase-full-item-title">Billing address</div>
                <p className="capitalize">{translateCivility(invoiceDetail.civility)} {invoiceDetail.firstName} {invoiceDetail.lastName}</p>
                <p>{invoiceDetail.lineOne}</p>
                {
                    invoiceDetail.lineTwo && <p>{invoiceDetail.lineTwo}</p>
                }
                <p>{invoiceDetail.postcode} {invoiceDetail.city}</p>
                <p>{invoiceDetail.country}</p>
            </div>
            <div className="purchase-separator"></div>
            <div className="purchase-full-item">
                <div className="purchase-full-item-title">Order detail</div>
                <table className="purchase-full-item-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit price</th>
                            <th>Total price</th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            purchaseLines.map(purchaseLine => (
                                <tr key={purchaseLine.id}>
                                    <td>{purchaseLine.product.designation}</td>
                                    <td>{purchaseLine.quantity}</td>
                                    <td>{priceFormater(purchaseLine.product.price)}</td>
                                    <td>{priceFormater(purchaseLine.totalPrice)}</td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
                <div className="purchase-full-item-total">
                    <div className="purchase-full-item-total-line">
                        <span>Total items</span>
                        <span>{priceFormater(fullPurchase.totalPrice)}</span>
                    </div>
                    <div className="purchase-full-item-total-line">
                        <span>Shipping cost</span>
                        <span>{priceFormater(fullPurchase.shippingInfo.price)}</span>
                    </div>
                    <div className="purchase-full-item-total-separator"></div>
                    <div className="purchase-full-item-total-line main">
                        <span>Total</span>
                        <span>{priceFormater(fullPurchase.shippingInfo.price + fullPurchase.totalPrice)}</span>
                    </div>
                </div>
            </div>
            <div className="purchase-separator"></div>
            {
                tracking && (
                    <>
                        <div className="purchase-full-item">
                            <div className="purchase-full-item-title">Tracking number</div>
                            <p>{tracking}</p>
                        </div>
                        <div className="purchase-separator"></div>
                    </>
                )
            }
        </>
    )
}