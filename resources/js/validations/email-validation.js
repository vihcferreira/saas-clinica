function isEmpty(value) {
    return value.trim() === "";
}

function emailIsValid(value) {
    const validator = {
        isValid: true,
        errorMessage: null,
    };

    if (isEmpty(value)) {
        validator.isValid = false;
        validator.errorMessage = "O campo email é obrigatório";
        return validator;
    }

    //VERIFICAÇÃO DE REGEX DO E-MAIL (ex: email123@email)
    const regex = new RegExp(
        "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$"
    );
    if (!regex.test(value)) {
        validator.isValid = false;
        validator.errorMessage = "E-mail inválido!";
        return validator;
    }

    return validator;
}
