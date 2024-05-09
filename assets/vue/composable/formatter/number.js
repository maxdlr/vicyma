export const useNumberFormatter = () => {
  const filterByNumberProperty = (
    arrayOfObjects,
    sortOrderBy,
    maxMatchesCount
  ) => {
    let filteredMatches = [];
    let filteredShownMatchCount = 0;
    let filteredTotalMatchCount = 0;

    for (const object of arrayOfObjects) {
      filteredTotalMatchCount++;
      if (filteredShownMatchCount < maxMatchesCount) {
        filteredMatches.push(object);
        filteredShownMatchCount++;
      }

      filteredMatches.sort((a, b) => {
        return a[sortOrderBy] - b[sortOrderBy];
      });
    }

    return {
      filteredMatches,
      filteredShownMatchCount,
      filteredTotalMatchCount,
    };
  };
  return { filterByNumberProperty };
};
