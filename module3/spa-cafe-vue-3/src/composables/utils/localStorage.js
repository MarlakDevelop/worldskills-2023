export function useLocalStorage(key, defaultValue) {
    const setValue = (newValue) => localStorage.setItem('key', JSON.stringify(newValue))
    let value = localStorage.getItem(key)
    if (localStorage.getItem(key) !== null) {
        setValue(defaultValue)
        value = localStorage.getItem(key)
    }
    value = JSON.parse(value)
    return [value, setValue]
}