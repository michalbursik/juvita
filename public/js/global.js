function getCurrentInput() {
    return window.localStorage.getItem('currentInput');
}

function setCurrentInput(value) {
    let currentInput = getCurrentInput();

    if (currentInput) {
        document.getElementById(currentInput).classList.remove('border-success');
    }

    window.localStorage.setItem('currentInput', value);

    document.getElementById(value).classList.add('border-success');
}

window.setCurrentInput = setCurrentInput;

window.getCurrentInput = getCurrentInput;
