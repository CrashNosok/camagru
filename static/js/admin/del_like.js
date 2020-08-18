let likes = document.querySelectorAll('.del_like');
for (let like of likes) {
    like.addEventListener('click', del_like);
}

async function del_like(event) {
    let like_id = +event.target.getAttribute('id').replace('like_', '');
    
    let info = {
        'id': like_id,
    };
    let response = await fetch('/camagru/admin/del_like.php', {
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
