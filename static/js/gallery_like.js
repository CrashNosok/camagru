document.addEventListener('DOMContentLoaded', ready_gallery);

function ready_gallery() {
    let hearts = document.querySelectorAll('.heart');

    for (let heart of hearts) {
        heart.addEventListener('click', gallery_set_like);
    }
}

async function gallery_set_like(event) {
    let elem = event.target;
    let count_like = elem.parentNode.querySelector('.heart_number');
    let photo_id = elem.getAttribute('id').replace('like_', '');

    if (elem.classList.contains('like_on')) {
        // если стоит лайк (надо его выключить)
        elem.classList.remove('like_on');
        elem.style.color = 'white';
        elem.style.background = 'red';
        elem.style.fontsize = '20px';

        let res = +count_like.innerHTML - 1;
        count_like.innerHTML = res;
    } else {
        elem.classList.add('like_on');
        elem.style.color = 'red';
        elem.style.background = 'white';
        elem.style.fontsize = '25px';

        let res = +count_like.innerHTML + 1;
        count_like.innerHTML = res;

    }

    let info = {
        'id': photo_id,
    };
    let response = await fetch('../set_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    let result = await response.text();
    // console.log(result);
}
