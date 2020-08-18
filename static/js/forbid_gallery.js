let 
    gallery_likes = document.querySelectorAll('.heart'),
    gallery_comment_buttons = document.querySelectorAll('.comment_sub'),
    popup_likes = document.getElementById('pop_heart'),
    popup_comments_button = document.getElementById('pop_add_comment'),
    arr = [];

for (let elem of gallery_likes) {
    elem.addEventListener('click', please_login);
}
for (let elem of gallery_comment_buttons) {
    elem.addEventListener('click', please_login);
}
popup_likes.addEventListener('click', please_login);
popup_comments_button.addEventListener('click', please_login);

function please_login() {
    alert('Please log in or register');
}
