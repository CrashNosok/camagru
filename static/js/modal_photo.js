const popupLinks = document.querySelectorAll('.popup_link');
const body = document.querySelector('body');
const lockPadding = document.querySelectorAll('.lock_padding');
const timeout = 500;

let unlock = true;

if (popupLinks.length > 0) {
    for (popupLink of popupLinks) {
        popupLink.addEventListener('click', function(e) {
            const curetPopup = document.getElementById('popup');
            popupOpen(curetPopup);
            e.preventDefault();
        });
    }
}

const popupCloseIcon = document.querySelectorAll('.close_popup');
if (popupCloseIcon.length > 0) {
    for (let i = 0; i < popupCloseIcon.length; i++) {
        let el = popupCloseIcon[i];
        el.addEventListener('click', function(e) {
            popupClose(el.closest('.popup'));
            e.preventDefault();
        });
    }
}

function popupOpen(curetPopup) {
    if (curetPopup && unlock) {
        const popupActive = document.querySelector('.popup.open');
        if (popupActive) {
            popupClose(popupActive, false);
        } else {
            bodyLock();
        }
        curetPopup.classList.add('open');
        curetPopup.addEventListener('click', function(e) {
            if (!e.target.closest('.popup_content')) {
                popupClose(e.target.closest('.popup'));
            }
        });
    }
}

function popupClose(popupActive, doUnlock=true) {
    if (unlock) {
        popupActive.classList.remove('open');
        if (doUnlock) {
            bodyUnlock();
        }
        let checkbox = document.getElementById('popup_check_menu');
        if (checkbox) {
            checkbox.checked = false;
        }
    }
}

function bodyLock() {
    body.classList.add('lock');

    unlock = false;
    setTimeout(function() {
        unlock = true;
    }, timeout);
}

function bodyUnlock() {
    setTimeout(function() {
        body.classList.remove('lock');
    }, timeout);

    unlock = false;
    setTimeout(function() {
        unlock = true;
    }, timeout);
}

document.addEventListener('keydown', function (e) {
    if (e.which === 27) {
        const popupActive = document.querySelector('.popup.open');
        if (popupActive) {
            popupClose(popupActive);
        }
    }
})
