function writeDown(number) {
    let currentInput = getCurrentInput();
    let input = document.getElementById(currentInput);

    if (
        !input.value.includes('.') &&
        parseFloat(input.value) === 0 &&
        number !== '.'
    ) {
        input.value = number;
    } else {
        input.value += number;
    }
}

function removeLast() {
    let currentInput = getCurrentInput();
    let input = document.getElementById(currentInput);

    if (input.value.length > 1) {
        input.value = input.value.substr(0, input.value.length - 1);
    } else {
        input.value = 0;
    }
}

function deleteValue() {
    let currentInput = getCurrentInput();
    let input = document.getElementById(currentInput);

    input.value = 0;
}

function submitForm() {
    let form = document.getElementById('warehouse_movements_form');

    form.submit();
}
