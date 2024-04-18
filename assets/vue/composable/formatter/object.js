import {objectPick} from "@vueuse/core";

export const useObjectFormatter = () => {
    const getPropertyValue = (object, property) => {
        const o = objectPick(object, [property], true);
        return Object.values(o)[0];
    };

    return {getPropertyValue};
};
