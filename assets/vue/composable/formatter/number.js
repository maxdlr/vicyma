export const getReviewAverage = (arrayOfNumbers) => {
    const arrayOfRates = arrayOfNumbers.map((rate) => {
        return rate.rate
    })


    let average = 0;
    for (const key in arrayOfRates) {
        average += arrayOfRates[key]
    }

    average = (average / arrayOfRates.length).toFixed(1)

    return isNaN(average) ? null : average
};
