package com.example.demo.form;

import jakarta.validation.constraints.*;
import java.time.LocalDate;
import lombok.Data;

@Data
public class UserForm {
    @DecimalMin("5")
    private String username;
    @Pattern(regexp="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,6}$")
    private String email;
    @Pattern(regexp="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,6}$")
    private String password;
@NotBlank
@NotNull(message = "Age is required")
@Min(value = 18, message = "Age must be 18 or older")
private Integer age;

    @Past(message = "Birthdate must be in the past")
    private LocalDate birthDate;
    @NotBlank
    @NotEmpty
    @AssertTrue(message = "You must accept the terms")
    private Boolean termsAccepted;
}
