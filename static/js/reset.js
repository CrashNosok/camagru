document.addEventListener('keyup', check);

let succ = document.getElementById('succsess');
if (succ) {
    document.getElementById('for_del').remove();
}

function add_styles(condition, btn) {
    if (condition) {
        btn.classList.add('active_btn');
        btn.style.cursor = 'pointer';
        btn.style.background = '#0096f6';
        return btn;
    } else {
        if (btn.classList.contains('active_btn')) {
            btn.classList.remove('active_btn');
            btn.style.cursor = 'default';
            btn.style.background = '#b3e0fc';
            return btn;
        }
    }
}

function check(event) {
    let
        login,
        email,
        code,
        btn,
        btn2,
        pas_1,
        pas_2;
    if (window.location.href.includes('?form=code')) {
        // code
        code = document.getElementById('code').value;
        pas_1 = document.getElementById('pas_1').value;
        pas_2 = document.getElementById('pas_2').value;
        btn = document.getElementById('submit_code');
        btn = add_styles(code != '' && pas_1 == pas_2 && pas_1.length >= 8, btn);
    } else {
        // user
        login = document.getElementById('login').value;
        email = document.getElementById('email').value;
        btn2 = document.getElementById('submit');

        let re = /^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i;
        let check_email = re.test(String(email).toLowerCase());
        btn2 = add_styles(login != '' && check_email, btn2);
    }
}