import {getPropertyValue} from "./object";

export const toTitle = (string) => {
    return string.charAt(0).toUpperCase() + string.substring(1);
};

export const truncate = (string, numberOfChars, suffix) => {
    return string.substr(0, numberOfChars) + suffix
}