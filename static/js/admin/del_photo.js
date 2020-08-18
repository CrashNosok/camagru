let photoes = document.querySelectorAll('.del_photo');
for (let photo of photoes) {
    photo.addEventListener('click', del_photo);
}

async function del_photo(event) {
    let photo_id = event.target.parentNode.querySelector('img').getAttribute('id');
    let info = {
        'id': photo_id,
    };
    let response = await fetch('/camagru/admin/del_photo.php', {
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
