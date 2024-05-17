export const toTitle = (string) => {
    return string.charAt(0).toUpperCase() + string.substring(1);
};

export const singularize = (string) => {
    if (string.charAt(string.length - 1) === 's') {
        return string.substring(0, string.length - 1)
    } else {
        return string;
    }
}

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