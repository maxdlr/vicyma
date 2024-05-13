export const clearEmptyLocaleStorage = () => {
    for (const localStorageKey in localStorage) {
        const item = localStorage.getItem(localStorageKey);
        if (["", ' ', '{}'].includes(item)) {
            localStorage.removeItem(localStorageKey)
        }
    }
}