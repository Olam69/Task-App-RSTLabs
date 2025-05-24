
// Set a cookie
function set_lightcookie(name, value, expirationDays, path) {
  const cookieValue = encodeURIComponent(value);
  let cookieString = name + "=" + cookieValue;

  if (expirationDays) {
    const expirationDate = new Date();
    expirationDate.setDate(expirationDate.getDate() + expirationDays);
    cookieString += "; expires=" + expirationDate.toUTCString();
  }

  if(path) cookieString+=path;

  document.cookie = cookieString;
}


// Get the value of a cookie
function get_lightcookie(name, isWarns=true) {
  const cookieName = name + "=";
  const cookieArray = document.cookie.split(";");

  for (let i = 0; i < cookieArray.length; i++) {
    let cookie = cookieArray[i];
    while (cookie.charAt(0) === " ") {
      cookie = cookie.substring(1);
    }
    if (cookie.indexOf(cookieName) === 0) {
      const cookieValue = decodeURIComponent(cookie.substring(cookieName.length));
      return cookieValue;
    }
  }

  if(isWarns===true) console.warn("No cookie named "+name+"... or not in this current path!");
  
  return null;
}


// Delete a cookie by setting its expiration to a past date
function del_lightcookie(name) {
  document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}


