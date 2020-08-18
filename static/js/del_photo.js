document.getElementById('menu_del_photo').addEventListener('click', del_photo);

async function del_photo(event) {
    let info = {
        'id': global_photo_id,
    };
    let response = await fetch('/camagru/del_photo.php', {
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
