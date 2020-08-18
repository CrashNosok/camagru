document.addEventListener('keyup', check);

function check(event) {
    let
        login = document.getElementById('login').value,
        password = document.getElementById('password').value,
        btn = document.getElementById('submit');
    if (login != '' && password.length >= 8) {
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