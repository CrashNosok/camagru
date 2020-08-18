async function set_like(preview, elem) {
    let count_like = document.getElementById('pop_heart_number');
    let count_preview_likes = +preview.innerHTML;
    let turn = 1;

    if (elem.classList.contains('like_on')) {
        // если стоит лайк (надо его выключить)
        elem.classList.remove('like_on');
        elem.style.color = 'white';
        elem.style.background = 'red';
        elem.style.fontsize = '20px';

        let res = +count_like.innerHTML - 1;
        count_like.innerHTML = res;

        // изменение в preview:
        preview.innerHTML = count_preview_likes - 1;
    } else {
        elem.classList.add('like_on');
        elem.style.color = 'red';
        elem.style.background = 'white';
        elem.style.fontsize = '25px';

        let res = +count_like.innerHTML + 1;
        count_like.innerHTML = res;

        preview.innerHTML = count_preview_likes + 1;
    }


    let info = {
        'id': global_photo_id,
    };
    let response = await fetch('/camagru/set_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    let result = await response.json();
}