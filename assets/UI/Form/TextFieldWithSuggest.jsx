import React from 'react';
import { useController } from 'react-hook-form';
import { Loader } from '../Icon/Loader';
import { SuggestList } from '../SuggestList';
import { useLabelDown } from '../../CustomHook/form/useLabelDown';

export const TextFieldWithSuggest = ({children, control, name, onChange, onSelect, error, customHookFetcher, render, additionalClass = '', ...props}) => {
    const {field} = useController({name, control});
    

    //suggest

    const [suggests, update, isFetchLoading, reset] = customHookFetcher();

    const handleChange = e => {
        //traitement custom
        if(onChange) {
            onChange(e);
        }
        // envoie la value au hook form
        field.onChange(e);
        //traitement spécifique pour address suggest
        update(e.target.value);
    }

    const handleSelect = item => {
        reset();
        onSelect(item);
    }


    //label down
    const [isLabelDown, handleFocus, handleBlur] = useLabelDown(field.value, field.onBlur);

    return (
        <div className={'form-group' + (error ? ' is-invalid': '')}>
            <div className={'input-wrapper '+ field.name  + (isLabelDown ? ' down': '')}>
                <label className="form-label" htmlFor={field.name}>{children}</label>
                <input 
                    ref={field.ref} 
                    onChange={handleChange} 
                    value={field.value} 
                    name={field.name}
                    onBlur={handleBlur}
                    onFocus={handleFocus}
                    type="text" 
                    className={`form-control with-loader ${additionalClass}`}  
                    id={field.name}  
                    {...props}
                />
                {
                    isFetchLoading && <Loader additionalClass="input-loader" />
                }
                {
                    suggests && (
                        <SuggestList 
                            suggests={suggests}
                            onClose={reset}
                            onSelect={handleSelect} // sélection au clavier, la sélection au click se place sur le SuggestItem
                            render={(suggest, isSelected) => render(suggest, isSelected, handleSelect)} 
                                                                                                                                           
                        />
                    )
                }
            </div>
            {error && <div className="form-error">{error}</div>}
        </div>
    )

}
