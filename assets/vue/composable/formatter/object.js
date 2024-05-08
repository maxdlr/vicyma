import {objectPick} from "@vueuse/core";

export const isEmpty = (obj) => {
    for (const prop in obj) {
        if (Object.hasOwn(obj, prop)) {
            return false;
        }
    }

    return true;
}
export const getPropertyValue = (object, property) => {
    const o = objectPick(object, [property], true);
    return Object.values(o)[0];
};
