import { createRoot } from 'react-dom/client';
import '../../styles/app.css';
import React from 'react';
import { RegisterForm } from '../../Components/Fr/Security/RegisterForm';



const registerForm = document.getElementById('register-form');
const registerFormRoot = createRoot(registerForm);
registerFormRoot.render(
    <RegisterForm csrfToken={registerForm.dataset.csrftoken} />
);