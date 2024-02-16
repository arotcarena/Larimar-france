import React from 'react';
import { createRoot } from 'react-dom/client';
import { Account } from '../../Components/Fr/Account';


const accountElt = document.getElementById('account');
const user = {
    email: JSON.parse(accountElt.dataset.email),
    civility: JSON.parse(accountElt.dataset.civility),
    firstName: JSON.parse(accountElt.dataset.firstname),
    lastName: JSON.parse(accountElt.dataset.lastname)
}

const accountRoot = createRoot(accountElt);
accountRoot.render(
    <Account dbUser={user} />
);

