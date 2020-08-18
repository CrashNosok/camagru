let comment_submits = document.querySelectorAll('.comment_sub');
for (let sub of comment_submits) {
    sub.addEventListener('click', comment_gallery);
}

let comment_buttons = document.querySelectorAll('.comment_text');
for (let but of comment_buttons) {
    but.addEventListener('keyup', check_gallegy);
}

function check_gallegy(event) {
    let comment_field = event.target.parentNode.querySelector('input[type=text]');
    let add_comment_but = comment_field.parentNode.querySelector('.comment_sub');
    let comment_text = comment_field.value;

    if (comment_text.length) {
        add_comment_but.classList.add('active_link');
        add_comment_but.style.cursor = 'pointer';
        add_comment_but.style.color = '#0096f6';
    } else {
        if (add_comment_but.classList.contains('active_link')) {
            add_comment_but.classList.remove('active_link');
            add_comment_but.style.cursor = 'default';
            add_comment_but.style.color = '#b3e0fc';
        }
    }
}

async function comment_gallery(event) {
    let comment_text = event.target.parentNode.querySelector('.comment_text').value;
    let photo_id = event.target.parentNode.getAttribute('id').replace('comment_photo_', '');
    let to_user = event.target.parentNode.parentNode.parentNode.querySelector('.name').innerHTML;

    if (comment_text.length == 0) {
        return;
    }
    // отправляем запрос
    let info = {
        'id': photo_id,
        'text': comment_text,
    };
    let response = await fetch('/camagru/set_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    // получаем ответ
    let result = await response.text();

    // обновляем превью
    let count_comments_elem = event.target.parentNode.parentNode.querySelector('.comment_number');
    let count_comments = +count_comments_elem.innerHTML;

    count_comments_elem.innerHTML = count_comments + 1;

    // чисти поле для сообщений:
    event.target.parentNode.querySelector('.comment_text').value = '';

    // отправляе сообщение
    send_mail(comment_text, to_user);
}
