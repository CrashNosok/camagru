let global_photo_id = null;

const popups = document.querySelectorAll('.popup_link');
for (let p of popups) {
    p.addEventListener('click', get_user);
}

function clear_popup_li() {
    let tmp_li_arr = document.querySelectorAll('#pop_comments li');
    if (tmp_li_arr.length) {
        for (let tmp_li of tmp_li_arr) {
            tmp_li.remove();
        }
    }
}

async function add_share(photo_id) {
    // twitter
    let twitter_link = document.getElementById('twitter_link');
    let new_twitter_src = twitter_link.getAttribute('href').replace('[URL]', encodeURIComponent(`http://localhost/camagru/show_image.php?i=${photo_id}`));
    twitter_link.setAttribute('href', new_twitter_src);
    
    // vk
    let vk_link = document.getElementById('vk_link');
    let new_vk_src = vk_link.getAttribute('href').replace('[URL]', encodeURIComponent(`http://localhost/camagru/show_image.php?i=${photo_id}`));
    vk_link.setAttribute('href', new_vk_src);

    // одноклассники
    let odnoklassniki_link = document.getElementById('odnoklassniki_link');
    let new_odnoklassniki_src = odnoklassniki_link.getAttribute('href').replace('[URL]', encodeURIComponent(`http://localhost/camagru/show_image.php?i=${photo_id}`));
    odnoklassniki_link.setAttribute('href', new_odnoklassniki_src);
}

function make_popup(response, photo_id) {
    if (response.error != false) {
        alert(response.error);
        return;
    }

    document.getElementById('pop_img').src = `data:image/jpeg;base64,${response.photo}`;
    document.getElementById('location').innerHTML = response.location;


    let comments_arr = response.comments;
    clear_popup_li();
    for (let comment of comments_arr) {
        let li = document.createElement('li');
        let text = comment.text;
        let new_text = '';
        for (let i = 0; i < text.length; i++) {
            if (text[i] == '@' && text[i+1] && text[i+1] != ' ' && text[i+1] != '\0') {
                new_text += '<span class="color_blue">';
                while (text[i] && text[i] != ' ' && text[i] != '\0') {
                    new_text += text[i];
                    i++;
                }
                new_text += '</span>';
            }
            if (i < text.length) {
                new_text += text[i];
            }
        }
        li.innerHTML = `
        <p class="comment_name">
            ${comment.username}
        </p>
        <p class="coment_text">
            ${new_text}
        </p>
        <p class="comment_pubdate">
            ${comment.pubdate}
        </p>
        `;
        document.getElementById('pop_comments').prepend(li);
    }

    let heart = document.getElementById('pop_heart');
    if (response.turn_like == true) {
        heart.classList.add('like_on');
        heart.style.color = 'red';
        heart.style.background = 'white';
        heart.style.fontsize = '25px';
    } else {
        if (heart.classList.contains('like_on')) {
            heart.classList.remove('like_on');
            heart.style.color = 'white';
            heart.style.background = 'red';
            heart.style.fontsize = '20px';
        }
    }

    document.getElementById('pop_heart_number').innerHTML = response.count_likes;
    document.getElementById('photo_pubdate').innerHTML = response.pubdate;
    document.getElementById('usersphoto').innerHTML = response.usersphoto;

    add_share(photo_id);
}

async function get_response_photo(photo_id) {
    let info = {
        'id': photo_id
    };
    let response = await fetch('/camagru/get_photo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json;charset=utf-8',
        },
        body: JSON.stringify(info),
    });
    let result = await response.json();
    return result;
}

async function get_user(event) {
    let photo_id = event.target.closest('li').getAttribute('id');
    if (!photo_id) {
        return;
    }
    global_photo_id = photo_id;
    let result = await get_response_photo(photo_id);
    make_popup(result, photo_id);
}
