document.getElementById('pop_heart').addEventListener('click', like);

async function like(event) {
    let elem = event.target;
    let preview = document.getElementById('puplic_'+global_photo_id);
    await set_like(preview, elem);
    let elem_like = document.getElementById('like_'+global_photo_id);
    if (elem_like.classList.contains('like_on')) {
        // если стоит лайк (надо его выключить)
        elem_like.classList.remove('like_on');
        elem_like.style.color = 'white';
        elem_like.style.background = 'red';
        elem_like.style.fontsize = '20px';
    } else {
        elem_like.classList.add('like_on');
        elem_like.style.color = 'red';
        elem_like.style.background = 'white';
        elem_like.style.fontsize = '25px';
    }
}