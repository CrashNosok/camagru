let dels = document.querySelectorAll('.del');

for (let del of dels) {
    del.addEventListener('click', prepare_user);
}

function prepare_user(event) {
    let user = event.target.parentNode;
    let user_id = +user.querySelector('.user_id').innerHTML;
    if (user_id) {
        del_user(user_id);
    }
}

async function del_user(user_id) {
    let info = {
        'del_user': user_id,
    };
    let response = await fetch('/camagru/admin/del_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    let result = await response.text();
    // console.log(result);
    location.reload();
}