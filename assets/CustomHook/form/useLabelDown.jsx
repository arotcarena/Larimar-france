import { useEffect, useState } from "react";



export const useLabelDown = (value, onBlur) => {
    const [isLabelDown, setLabelDown] = useState(false);

    useEffect(() => {
        if(value === '') {
            setLabelDown(true);
        }
    }, []);

    const handleFocus = e => {
        if(isLabelDown) {
            setLabelDown(false);
        }
    };
    const handleBlur = e => {
        //on avertit hook form
        onBlur();
        
        if(e.target.value === '') {
            setLabelDown(true);
        }
    };

    return [isLabelDown, handleFocus, handleBlur];
}