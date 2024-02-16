
import '../../styles/app.css';


import React from 'react';
import { createRoot } from 'react-dom/client';
import { ProductIndex } from '../../Components/En/Shop/ProductIndex';



const productIndex = document.getElementById('product-index');

const categoryId = productIndex.dataset.categoryid ?? null;
const subCategoryId = productIndex.dataset.subcategoryid ?? null;

const productIndexRoot = createRoot(productIndex);
productIndexRoot.render(
    <ProductIndex categoryId={categoryId} subCategoryId={subCategoryId} />
);