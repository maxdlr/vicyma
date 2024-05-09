export const goTo = (url, confirmMsg = null) => {
    if (confirmMsg) confirm(confirmMsg)
    location.href = url
}