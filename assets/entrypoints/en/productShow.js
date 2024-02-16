import React from 'react';
import { createRoot } from "react-dom/client";
import { ProductImgCarousel } from "../../Components/En/Shop/ProductShow/ProductImgCarousel";
import { FingerSizeChoice } from '../../Components/En/Shop/ProductShow/FingerSizeChoice';
import { SuggestedProductsList } from '../../Components/En/Shop/ProductShow/SuggestedProductsList';



const carousel = document.getElementById('product-img-carousel');

let pictures = [];

for (let index = 0; index <= 3; index++) {
    const picture = carousel.querySelector('#picture'+index);
    if(picture) {
        pictures.push({
            src: JSON.parse(picture.dataset.src),
            alt: JSON.parse(picture.dataset.alt)
        });
    }
}

const carouselRoot = createRoot(carousel);
carouselRoot.render(
    <ProductImgCarousel pictures={pictures} />
);


if(document.getElementById('fingerSize-choice')) {
    const button = document.getElementById('fingerSize-choice');
    const publicRef = JSON.parse(button.dataset.publicref);
    const root = createRoot(button);
    root.render(<FingerSizeChoice publicRef={publicRef} />);
}


const suggestedProductElt = document.getElementById('show-suggested-products');
const productId = suggestedProductElt.dataset.productid;
const suggestedProductRoot = createRoot(suggestedProductElt);
suggestedProductRoot.render(<SuggestedProductsList productId={productId} />)
