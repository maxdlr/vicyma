import {getPropertyValue} from "./object";

export const useStringFormatter = () => {
    const toTitle = (string) => {
        return string.charAt(0).toUpperCase() + string.substring(1);
    };

    const getCurrency = () => {
        return "€";
    };

    const getPriceTypeLabel = (isExcludingTax) => {
        return isExcludingTax ? "HT" : "TTC";
    };

    const isString = (string) => {
        return typeof string === "string";
    };

    const filterByStringProperty = (
        arrayOfObjects,
        filterBy,
        sortOrderBy,
        query = "",
        maxMatchesCount
    ) => {
        const re = RegExp(`.*${query.toLowerCase().split("").join(".*")}.*`);

        let filteredMatches = [];
        let filteredShownMatchCount = 0;
        let filteredTotalMatchCount = 0;

        for (const object of arrayOfObjects) {
            const filterByProperty = getPropertyValue(object, filterBy);

            if (filterByProperty.toLowerCase().match(re) || query === "") {
                filteredTotalMatchCount++;
                if (filteredShownMatchCount < maxMatchesCount) {
                    filteredMatches.push(object);
                    filteredShownMatchCount++;
                }
            }
        }

        filteredMatches.sort((a, b) => {
            return ("" + a[sortOrderBy]).localeCompare(b[sortOrderBy]);
        });

        return {
            filteredMatches,
            filteredShownMatchCount,
            filteredTotalMatchCount,
        };
    };

    return {
        toTitle,
        getCurrency,
        getPriceTypeLabel,
        filterByStringProperty,
        isString,
    };
};
