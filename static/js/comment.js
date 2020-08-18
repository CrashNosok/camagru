document.addEventListener('keyup', check);
document.getElementById('pop_add_comment').addEventListener('click', comment);

function check(event) {
    let comment_text = document.getElementById('pop_comment_text').value;
    let add_comment_but = document.getElementById('pop_add_comment');

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

async function comment(event) {
    let comment_text = document.getElementById('pop_comment_text').value;
    if (comment_text.length == 0) {
        return;
    }

    // отправляем запрос
    let info = {
        'id': global_photo_id,
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
    // update_comment_priview();
    let tmp_li_arr = document.querySelectorAll('#pop_comments li');
    document.getElementById('preview_comment_'+global_photo_id).innerHTML = tmp_li_arr.length + 1;

    // обновляем попап
    let result_for_popup = await get_response_photo(global_photo_id);
    make_popup(result_for_popup);

    // чистим поле для сообщений:
    document.getElementById('pop_comment_text').value = '';

    // отправляем сообщение
    send_mail(comment_text, result_for_popup.usersphoto);
}
