import { useEffect, useState } from "react";

export const useTouchingListener = () => {
    //utilisation du tactile
    const [isTouching, setTouching] = useState(false);
    useEffect(() => {
        if (window.matchMedia('(pointer: coarse)').matches) {
            // Appareil tactile
            setTouching(true);
        }
    }, []);
    return isTouching;
}