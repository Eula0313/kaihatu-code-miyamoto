package com.example.demo.validator;

import com.example.demo.form.SampleForm;
import org.springframework.stereotype.Component;
import org.springframework.validation.Errors;
import org.springframework.validation.Validator;

@Component
public class PassValidator implements Validator {
    @Override
    public boolean supports(Class<?> clazz) {
        return SampleForm.class.isAssignableFrom(clazz);
    }

    @Override
    public void validate(Object target, Errors errors) {
        SampleForm form = (SampleForm) target;

        if(form.getPassword()!=null && form.getConfirmPassword()!=null) {
            if(!form.getPassword().equals(form.getConfirmPassword())) {
                errors.reject("PassValidator.passmsg");
            }
        }
    }
}