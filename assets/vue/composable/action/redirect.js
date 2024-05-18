export const goTo = (url, confirmMsg = null) => {
    if (confirmMsg) {
        if (confirm(confirmMsg)) location.href = url
    } else {
        location.href = url
    }
}