document.addEventListener('keyup', check);

function check(event) {
    let
        login = document.getElementById('login').value,
        mail = document.getElementById('mail').value,
        pas_1 = document.getElementById('pas_1').value,
        pas_2 = document.getElementById('pas_2').value,
        btn = document.getElementById('submit');
    let re = /^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i;
    let check_email = re.test(String(mail).toLowerCase());
    if (login != '' && mail != '' && pas_1.length >= 8 && pas_2.length >= 8 && check_email) {
        btn.classList.add('active_btn');
        btn.style.cursor = 'pointer';
        btn.style.background = '#0096f6';
    } else {
        if (btn.classList.contains('active_btn')) {
            btn.classList.remove('active_btn');
            btn.style.cursor = 'default';
            btn.style.background = '#b3e0fc';
        }
    }
}
