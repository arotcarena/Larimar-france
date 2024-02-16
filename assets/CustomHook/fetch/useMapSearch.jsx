import { useState } from "react"
import { apiFetch } from "../../functions/api";
import { convertMapResult } from "../../functions/apiMapConvertor";

export const useMapSearch = () => {
    

    const [data, setData] = useState(null);
    const [isLoading, setLoading] = useState(false);

    const [timer, setTimer] = useState(null);

    const reset = () => {
        setLoading(false);
        setData(null);
    }
    
    const update = q => {
        if(timer) {
            clearTimeout(timer);
        }
        if(!q || q.length < 2) {
            reset();
            return;
        }
        const currentTimer = setTimeout(async () => {
            setLoading(true);
            try {
                const result = await apiFetch('https://api-adresse.data.gouv.fr/search/?limit=5&q='+q);
                if(result.length === 0) {
                    reset();
                    return;
                }
                setData(convertMapResult(result));
            } catch(e) {
                //
            }
            setLoading(false);
        }, 300);
        setTimer(currentTimer);
    } 
    return [data, update, isLoading, reset];
}



