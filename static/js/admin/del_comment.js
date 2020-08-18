let comments = document.querySelectorAll('.del_comment');
for (let comment of comments) {
    comment.addEventListener('click', del_comment);
}

async function del_comment(event) {
    let comment_id = +event.target.getAttribute('id').replace('comment_', '');
    
    let info = {
        'id': comment_id,
    };
    let response = await fetch('/camagru/admin/del_comment.php', {
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
