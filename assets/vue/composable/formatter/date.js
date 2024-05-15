const last12Months = () => {
    const now = new Date();
    const startYear = new Date()
    startYear.setFullYear(now.getFullYear() - 1)

    return {
        name: 'Last 12 months',
        value: {
            start: startYear,
            end: now
        }
    }
}

const thisYear = () => {
    const start = new Date();
    start.setDate(1)
    start.setMonth(0)

    const end = new Date();
    end.setDate(31)
    end.setMonth(11)

    return {
        name: 'This year',
        value: {
            start: start,
            end: end
        }
    }
}

const lastYear = () => {
    const start = new Date();
    start.setDate(1)
    start.setMonth(0)
    start.setFullYear(start.getFullYear() - 1)

    const end = new Date();
    end.setDate(31)
    end.setMonth(11)
    end.setFullYear(end.getFullYear() - 1)

    return {
        name: 'Last year',
        value: {
            start: start,
            end: end
        }
    }
}

const nextYear = () => {
    const start = new Date();
    start.setDate(1)
    start.setMonth(0)
    start.setFullYear(start.getFullYear() + 1)

    const end = new Date();
    end.setDate(31)
    end.setMonth(11)
    end.setFullYear(end.getFullYear() + 1)

    return {
        name: 'Next year',
        value: {
            start: start,
            end: end
        }
    }
}

const next6Months = () => {
    const start = new Date();
    const end = new Date();
    end.setMonth(start.getMonth() + 6)

    return {
        name: 'Next 6 months',
        value: {
            start: start,
            end: end
        }
    }
}

const last6Months = () => {
    const start = new Date();
    start.setMonth(start.getMonth() - 6)
    const end = new Date();

    return {
        name: 'Last 6 months',
        value: {
            start: start,
            end: end
        }
    }
}

export const getDateOptions = () => {
    return [
        last12Months(),
        thisYear(),
        lastYear(),
        nextYear(),
        next6Months(),
        last6Months()
    ]
}