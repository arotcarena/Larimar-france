import React, { useEffect, useState } from "react";
import {
  PaymentElement,
  // LinkAuthenticationElement,
  useStripe,
  useElements
} from "@stripe/react-stripe-js";
import '../../../../../styles/Checkout/paymentForm.css';
import { ApiError, apiFetch } from "../../../../../functions/api";
import { Loader } from "../../../../../UI/Icon/Loader";
import { priceFormater } from "../../../../../functions/formaters";
import { CheckIcon } from "../../../../../UI/Icon/CheckIcon";


export const PaymentForm = ({piSecret, checkoutData, cart}) => {
  const stripe = useStripe();
  const elements = useElements();
  // sert pour enregistrer la carte (link)
  // const [email, setEmail] = useState('');
  const [errors, setErrors] = useState(null);
  //inutile voir ci-dessous
  // const [message, setMessage] = useState(null); 
  const [isLoading, setIsLoading] = useState(false);


  //agreeCGV
  const [agreeCGV, setAgreeCGV] = useState(false);
  const [agreeCGVError, setAgreeCGVError] = useState(false);
  const handleAgreeCGVChange = e => {
    setAgreeCGV(agreeCGV => !agreeCGV);
  }
  useEffect(() => {
    if(agreeCGV && agreeCGVError) {
      setAgreeCGVError(false);
    }
  }, [agreeCGV]);

  //INUTILE CAR JE FAIS UNE REDIRECTION DONC CES MESSAGES NE SERONT PAS AFFICHES 
  // useEffect(() => {
  //   if (!stripe) {
  //     return;
  //   }

  //   const clientSecret = new URLSearchParams(window.location.search).get(
  //     "payment_intent_client_secret"
  //   );

  //   if (!clientSecret) {
  //     return;
  //   }

  //   stripe.retrievePaymentIntent(clientSecret).then(({ paymentIntent }) => {
  //     switch (paymentIntent.status) {
  //       case "succeeded":
  //         setMessage("Paiement réussi !");
  //         break;
  //       case "processing":
  //         setMessage("Paiement en cours. Veuillez rester sur cette page");
  //         break;
  //       case "requires_payment_method":
  //         setMessage("Votre paiement a échoué, veuillez recommencer.");
  //         break;
  //       default:
  //         setMessage("Quelque chose n\'a pas fonctionné.");
  //         break;
  //     }
  //   });
  // }, [stripe]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!stripe || !elements || isLoading) {
      // Stripe.js hasn't yet loaded.
      // Make sure to disable form submission until Stripe.js has loaded.
      return;
    }
    if(!agreeCGV) {
      setAgreeCGVError(true);
      return;
    }

    setErrors(null);
    setIsLoading(true);

    //lastVerificationBeforePayment : 
    // on vérifie le stock (s'il est insuffisant ça renvoie une erreur mais avant on met à jour le cart et la purchase) 
    // et si on a pas déjà payé cette purchase
    try {
      await apiFetch('/en/api/purchase/lastVerificationBeforePayment', {
        method: 'POST',
        body: JSON.stringify({
          piSecret: piSecret,
          checkoutData: checkoutData
        })
      });
    } catch(e) {
      if(e instanceof ApiError) {
        if(e.errors.target) {
          window.location.href = e.errors.target;
        } else {
          setErrors(e.errors);
        } 
      } else {
        setErrors(['The form is invalid']);
      }
      setIsLoading(false);
      return;
    }

    //tentative de paiement
    const { error } = await stripe.confirmPayment({
      elements,
      confirmParams: {
        // Make sure to change this to your payment completion page
        return_url: 'https://localhost:8000/en/order-successfull'
      },
    });

    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
      setErrors([error.message]);
    } else {
      setErrors(['Payment failed. If the issue persists, please contact your bank.']);
    }

    setIsLoading(false);
  };


  const paymentElementOptions = {
    layout: "tabs"
  }

  return (
    <form id="payment-form" onSubmit={handleSubmit}>
      {/* <LinkAuthenticationElement
        id="link-authentication-element"
        onChange={(e) => setEmail(e.target.value)}
      /> */}
      <PaymentElement id="payment-element" options={paymentElementOptions} />
      <div className={'checkbox-group' + (agreeCGVError ? ' is-invalid': '')}>
          <input className="form-checkbox" id="checkboxRememberMe" type="checkbox" name="agreeCGV" onChange={handleAgreeCGVChange} />
          <label htmlFor="checkboxRememberMe" className="form-label">
              <div className="custom-checkbox" role="checkbox" aria-labelledby="checkbox-label">
                  <CheckIcon />
              </div>
              <span id="checkbox-label">I agree terms of sales</span>
          </label>
          {
            agreeCGVError && <div className="form-error">You must check this box</div>
          }
      </div>
      <button className={'form-button pay-button' + (isLoading || !stripe || !elements ? ' disabled': '')} disabled={isLoading || !stripe || !elements} id="submit">
        <span id="button-text">
          {
            isLoading 
            ?
            <>
              <Loader />
              <span className="no-opacity">Payment in progress... Please stay on this page</span>
            </>
            :
            <span>
                Pay {cart ? priceFormater(cart.totalPrice): ''}
            </span>
          }
        </span>
      </button>
      {/* Show any error or success messages */}
      {
        errors && (
          <div className="info-group">
            {
              errors.map((error, index) => <div key={index} className="form-error">{error}</div>)
            }
          </div>
        )
      }
    </form>
  );
}