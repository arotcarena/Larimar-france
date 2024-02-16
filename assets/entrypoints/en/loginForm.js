import { createRoot } from 'react-dom/client';
import '../../styles/app.css';
import { LoginForm } from '../../Components/En/Security/LoginForm';
import React from 'react';



const loginForm = document.getElementById('login-form');
const loginFormRoot = createRoot(loginForm);
loginFormRoot.render(
    <LoginForm lastUsername={JSON.parse(loginForm.dataset?.lastusername)} csrfToken={loginForm.dataset.csrftoken} />
);