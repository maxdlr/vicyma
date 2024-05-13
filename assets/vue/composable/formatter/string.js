import {getPropertyValue} from "./object";

export const toTitle = (string) => {
    return string.charAt(0).toUpperCase() + string.substring(1);
};

export const truncate = (string, numberOfChars, suffix) => {
    return string.substr(0, numberOfChars) + suffix
}

export const implode = (array) => {
    let string = '';

    for (let i = 0; i < array.length; i++) {
        if (i === array.length - 1 && array.length > 1) {
            string += ' and '
        }
        string += array[i];
        if (i < array.length - 2) {
            string += ', ';
        }
    }

    return string;
}