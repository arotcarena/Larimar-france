import React from 'react';
import { useController } from 'react-hook-form';
import { useLabelDown } from '../../CustomHook/form/useLabelDown';





export const TextField = ({children, control, name, error, additionalClass = '', type = 'text', ...props}) => {
    const {field} = useController({name, control});

    const [isLabelDown, handleFocus, handleBlur] = useLabelDown(field.value, field.onBlur);

    return (
        <div className={'form-group' + (error ? ' is-invalid': '')}>
            <div className={'input-wrapper '+ field.name  + (isLabelDown ? ' down': '')}>
                <label className="form-label" htmlFor={field.name}>{children}</label>
                <input 
                    ref={field.ref} 
                    onChange={field.onChange} 
                    value={field.value} 
                    name={field.name}
                    onBlur={handleBlur}
                    onFocus={handleFocus}
                    type={type}
                    className={`form-control ${additionalClass}`} 
                    id={field.name}  
                    {...props}
                />
            </div>
            {error && <div className="form-error">{error}</div>}
        </div>
    )
}

 



// export const TextField = ({children, additionnalClass, type, name, value, onChange, errors, ...props}) => {

//     const handleChange = e => {
//         onChange(e.currentTarget.name, e.currentTarget.value);
//     }

//     return (
//         <div className={'form-group' + (errors ? ' is-invalid': '')}>
//             <label className="form-label" htmlFor={name}>{children}</label>
//             <input className={`form-control ${additionnalClass}`} id={name} type={type} name={name} value={value} onChange={handleChange} {...props} />
//             {
//                 errors && (
//                     <ul className="form-error">
//                         {errors.map((error, index) => <li key={index}>{error}</li>)}
//                     </ul>
//                 )
//             }
//         </div>
//     )
// }


export const TextFieldWithTransform = ({transformer, children, additionnalClass, type, name, value, onChange, errors, ...props}) => {
    
    const handleChange = e => {
        onChange(
            e.currentTarget.name, 
            transformer.reverseTransform(e.currentTarget.value)
        );
    }
    
    return (
        <div className={'form-group' + (errors ? ' is-invalid': '')}>
            <label className="form-label" htmlFor={name}>{children}</label>
            <input className={`form-control ${additionnalClass}`} id={name} type={type} name={name} value={transformer.transform(value)} onChange={handleChange} {...props} />
            {
                errors && (
                    <ul className="form-error">
                        {errors.map((error, index) => <li key={index}>{error}</li>)}
                    </ul>
                )
            }
        </div>
    )
}