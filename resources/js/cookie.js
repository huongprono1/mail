// cookie.js
/**
 * Thiết lập cookie
 * @param {string} name
 * @param {string} value
 * @param {number} days
 */
function setCookie(name, value, days) {
    let expires = '';
    if (typeof days === 'number') {
        const date = new Date();
        date.setTime(date.getTime() + days * 86400000);
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}${expires}; path=/; SameSite=Lax`;
}

function getCookie(name) {
    const key = encodeURIComponent(name) + '=';
    return document.cookie.split('; ').reduce((res, part) => {
        return part.startsWith(key)
            ? decodeURIComponent(part.slice(key.length))
            : res;
    }, null);
}

function hasCookie(name) {
    return getCookie(name) !== null;
}

function deleteCookie(name) {
    setCookie(name, '', -1);
}

// Chỉ export những gì bạn cần dùng
export {setCookie, getCookie, hasCookie, deleteCookie};
