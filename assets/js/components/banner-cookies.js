// target  the cookies-banner
const cookieBanner = document.getElementById('cookie-banner');

// target de accept button
const cookieAcceptButton = document.getElementById('cookie-accept');

// hide tje cookies banner
function hideCookieBanner() {
    cookieBanner.style.display = 'none';
}

// add an event listner to the accept button
cookieAcceptButton.addEventListener('click', function() {
    // Cacher la bannière de cookies lorsque le bouton est cliqué
    hideCookieBanner();
});

// check if the user has accepted the cookies
const hasAcceptedCookies = localStorage.getItem('cookieAccepted');

if (!hasAcceptedCookies) {
    // display the cookies banner if it has not been accepted yes
    cookieBanner.style.display = 'block';
}
