document.getElementById('pop_heart').addEventListener('click', like);

function like(event) {
    let elem = event.target;
    let preview = document.getElementById('preview_'+global_photo_id);
    set_like(preview, elem);
}
