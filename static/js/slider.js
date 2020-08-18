for (let i = 0; i < slides.length; i++) {
    slider[i] = slides[i].src;
    slides[i].remove();
}
for (let i = 0; i < count_masc + 2; i++) {
    draw_slider_left(i-1);
}

function del_mask() {
    if (mask_elem.target) {
        mask_elem.target.classList.remove('add_border');
        mask_elem = set_default_mask_elem();
        if (my_stream) {
            button_activation(document.getElementById('del_photo'));
            button_activation(document.getElementById('capture'));
        }
    }
}

function draw_slider_left(offset) {
    let
        img = document.createElement('img');
    img.src = slider[step];
    img.classList.add('slide_single');
    img.style.left = offset * box_size + 'px';
    document.getElementById('slide').appendChild(img);
    if (step + 1 >= slider.length) {
        step = 0;
    } else {
        step++;
    }
}

function draw_slider_right(offset) {
    let
        img = document.createElement('img');
    img.src = slider[step];
    img.classList.add('slide_single');
    img.style.left = offset * box_size + 'px';
    document.getElementById('slide').prepend(img);
    if (step <= 0) {
        step = slider.length - 1;
    } else {
        step--;
    }
}

function left_slider() {
    del_mask();
    document.getElementById('arrow').onclick = null;
    let
        slides2 = document.querySelectorAll('.slide_single'),
        offset2 = 0;
    for (let i = 0; i < slides2.length; i++) {
        slides2[i].style.left = offset2 * box_size - box_size*2 + 'px';
        offset2++;
    }
    setTimeout(function() {
        slides2[0].remove();
        draw_slider_left(count_masc);
        document.getElementById('arrow').onclick = left_slider;
    }, 400);
}

function right_slider() {
    del_mask();
    document.getElementById('arrow2').onclick = null;
    let
        slides2 = document.querySelectorAll('.slide_single'),
        offset2 = count_masc+1;
    for (let i = count_masc+1; i >= 0; i--) {
        slides2[i].style.left = offset2 * box_size + 'px';
        offset2--;
    }
    setTimeout(function() {
        slides2[count_masc+1].remove();
        draw_slider_right(-1);
        document.getElementById('arrow2').onclick = right_slider;
    }, 400);
}

document.getElementById('arrow').onclick = left_slider;
document.getElementById('arrow2').onclick = right_slider;