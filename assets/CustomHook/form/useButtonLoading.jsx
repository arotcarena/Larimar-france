import { useState } from "react"

export const useButtonLoading = () => {
    const [loading, setLoading] = useState(false);
    const handleButtonClick = e => {
        if(loading) {
            e.preventDefault();
            return;
        }
        setLoading(true);
    };
    return [loading, handleButtonClick];
}