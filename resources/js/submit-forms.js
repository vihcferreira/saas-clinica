const form = document.querySelector('#form');

if (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        const fields = [
            {
                id: "email",
                label: "Email",
                validator: emailIsValid,
            },
            {
                id: "password",
                label: "Senha",
                validator: passwordIsValid,
            },
        ];

        let isFormValid = true;

        fields.forEach(function (field) {
            const input = document.getElementById(field.id);
            const inputGroup = input.closest('.mb-3');
            const inputValue = input.value;
            const errorSpan = inputGroup.querySelector('.error');

            errorSpan.innerHTML = '';

            const fieldValidator = field.validator(inputValue);

            if(!fieldValidator.isValid) {
                errorSpan.innerHTML = fieldValidator.error;
                inputGroup.classList.add('invalid');
                isFormValid = false;
            } else {
                inputGroup.classList.add('valid');
            }
        })

        if(isFormValid) {
            form.submit();
        }
    });
}