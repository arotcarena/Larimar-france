import { useState } from "react"
import { apiFetch } from "../../functions/api";
import { convertCountryResult } from "../../functions/apiMapConvertor";

export const useCountrySearch = () => {
    

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
                const result = await apiFetch('https://restcountries.com/v3.1/translation/'+q+'?fields=name,translations,cca2,continents');
                setData(convertCountryResult(result));
            } catch(e) {
                reset();
                return;
            }
            setLoading(false);
        }, 300);
        setTimer(currentTimer);
    } 
    return [data, update, isLoading, reset];
}
