import React from 'react';
import { useController } from 'react-hook-form';


export const RadioFields = ({control, name, children, choices, selected, error, isRequiredField, ...props}) => {
    return (
        <div className={'form-group' + (error ? ' is-invalid': '')}>
            <label className="form-label">{children}</label>
            <div className="radio-group-wrapper">
                {
                    Object.entries(choices).map(([label, value]) => (
                        <RadioField key={value} value={value} isChecked={selected === value} error={error} control={control} name={name} {...props}>{label}</RadioField>
                    ))
                }
                {
                    isRequiredField && <span className="asterix">*</span>
                }
            </div>
            {error && <div className="form-error">{error}</div>}
        </div>
    )
}

export const RadioField = ({children, control, name, value, error, additionalClass = '', isChecked}) => {
    const {field: {...props}} = useController({control, name});

    return (
        <div className={'radio-group' + (error ? ' is-invalid': '')}>
            <input 
                {...props}
                value={value} 
                type="radio" 
                className={`form-radio ${additionalClass}`} 
                id={value}  
                defaultChecked={isChecked}
                />
            <label className="form-label" htmlFor={value}>
                <div className="custom-radio" role="radio" aria-labelledby="radio-label">
                    <div className="custom-radio-core"></div>
                </div>
                <span className="radio-label">{children}</span>
            </label>
        </div>
    )
}
