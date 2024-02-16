import React, { useEffect, useRef, useState } from 'react';
import { SiteConfig } from '../../../../../Config/SiteConfig';
import '../../../../../styles/Checkout/mondialRelay.css';
import { FormButton } from '../../../../../UI/Form/FormButton';

export const RelayChoiceForm = ({deliveryAddress, cart, setCheckoutData, setChoosingRelay, forwardStep}) => {


    useEffect(() => {
        const weight = cart.count * SiteConfig.ARTICLE_WEIGHT;

        jQuery(() => {  

            // PRECISER LE BON CODE ISO SELON LE PAYS

            // Charge le widget dans la DIV d'id "Zone_Widget" avec les paramètres indiqués  
            // et renverra le Point Relais sélectionné par l'utilisateur dans le champs d'ID "Retour_Widget"  
                $("#mondial-relay").MR_ParcelShopPicker({ 
                        Target: "#return-mondial-relay", // Selecteur JQuery de l'élément dans lequel sera renvoyé l'ID du Point Relais sélectionné (généralement un champ input hidden)  
                        Brand: "CC228HIO", // Votre code client Mondial Relay  
                        Country: deliveryAddress.iso, // Code ISO 2 lettres du pays utilisé pour la recherche,
                        PostCode: deliveryAddress.postcode,
                        Weight: weight,
                        OnNoResultReturned: handleNoResult,
                        OnParcelShopSelected: handleSelect,
                        // Habiller le widget aux couleurs Mondial Relay (thème par défaut si ce paramètre n'est pas renseigné)  
                        Theme: "mondialrelay", // Mettre la valeur "inpost" (en minuscule) pour utiliser le thème graphique Inpost
                        Responsive: true,  
                        ShowResultsOnMap: false,   
                });  
        }); 
        

    }, []);
    
    const handleNoResult = () => {
        mondialRelayWrapperRef.current.classList.add('visible');
    };

    

    const [selectedRelay, setSelectedRelay] = useState(null);
    const handleSelect = data => {
        setSelectedRelay({
            name: data.Nom,
            lineOne: data.Adresse1,
            lineTwo: data.Adresse2,
            postcode: data.CP,
            city: data.Ville
        });
    };

    const handleSubmit = e => {
        const form = e.target;
        const formData = new FormData(form);
        setCheckoutData(checkoutData => ({
            ...checkoutData,
            deliveryMethod: {
                ...checkoutData.deliveryMethod,
                relay: {
                    id: formData.get('relayId'),
                    ...selectedRelay
                }
            }
        }));
        forwardStep();
        setChoosingRelay(false);
    };


    const mondialRelayWrapperRef = useRef(null);

    return (
        <>
            <div ref={mondialRelayWrapperRef} id="mondial-relay">
            </div>
            <form onSubmit={handleSubmit}>
                <input type="hidden" name="relayId" id="return-mondial-relay"/>
                <FormButton>Submit</FormButton>
            </form>
        </>
    )
}