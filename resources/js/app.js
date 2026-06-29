

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const formatStudentNumber = (value, shouldAutoHyphen = false) => {
    const raw = String(value).toUpperCase().replace(/[^A-Z0-9]/g, '');
    let firstDigits = '';
    let campusCode = '';
    let lastDigits = '';

    for (const character of raw) {
        if (firstDigits.length < 2) {
            if (/\d/.test(character)) {
                firstDigits += character;
            }

            continue;
        }

        if (campusCode.length < 2) {
            if (/[A-Z]/.test(character)) {
                campusCode += character;
            }

            continue;
        }

        if (lastDigits.length < 4 && /\d/.test(character)) {
            lastDigits += character;
        }
    }

    let formatted = firstDigits;

    if (firstDigits.length === 2 && (campusCode.length > 0 || shouldAutoHyphen)) {
        formatted += `-${campusCode}`;
    }

    if (campusCode.length === 2 && (lastDigits.length > 0 || shouldAutoHyphen)) {
        formatted += `-${lastDigits}`;
    }

    return formatted.slice(0, 10);
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-student-number-format]').forEach((input) => {
        input.value = formatStudentNumber(input.value);

        input.addEventListener('input', (event) => {
            const isDeleting = event.inputType?.startsWith('delete') ?? false;
            const formattedValue = formatStudentNumber(input.value, !isDeleting);

            if (input.value !== formattedValue) {
                input.value = formattedValue;
            }
        });

        input.addEventListener('blur', () => {
            input.value = formatStudentNumber(input.value);
        });
    });
});
