export const getAverage = (arrayOfNumbers) => {
    let average = 0;
    for (const key in arrayOfNumbers) {
        average += arrayOfNumbers[key]
    }

    average = (average / arrayOfNumbers.length).toFixed(1)

    return isNaN(average) ? null : average
};
