import { useForm } from "react-hook-form";
import { yupResolver } from '@hookform/resolvers/yup';


export const useFormWithValidation = (validationSchema, defaultValues = {}) => {
    const { register, handleSubmit, setValue, setError, control, formState:{ errors, isSubmitting } } = useForm({
        defaultValues: defaultValues,
        mode: 'onTouched',
        resolver: yupResolver(validationSchema)
      });

    return { register, handleSubmit, errors, setError, isSubmitting, setValue, control }
}