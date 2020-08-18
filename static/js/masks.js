document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('slide').onclick = mask_selection;

    window.onmousemove = function(e) {
        mouse.win_x = e.pageX;
        mouse.win_y = e.pageY;
    }
    
    canvas.onmousemove = function(e) {
        // мышь относительно canvas
        mouse.x = e.pageX - e.target.offsetLeft;
        mouse.y = e.pageY - e.target.offsetTop;
    }
    
    canvas.onmousedown = function(e) {
        mouse.down = true;
    }
    
    canvas.onmouseup = function(e) {
        mouse.down = false;
        selected = false;
    }
});

function is_cursor_in_mask() {
    return  mouse.x > mask_elem.x && mouse.x < mask_elem.x + mask_elem.width && 
        mouse.y > mask_elem.y && mouse.y < mask_elem.y + mask_elem.height;
}

function stroke_mask(mask) {
    context.strokeStyle = 'blue';
    context.lineWidth = 3;
    context.strokeRect(mask.x, mask.y, mask.width, mask.height);
}

function button_activation(but) {
    if (mask_elem.target) {
        but.classList.remove('button_dnw');
    } else {
        but.classList.add('button_dnw');
    }
}

function button_on(but) {
    if (but.classList.contains('button_dnw')) {
        but.classList.remove('button_dnw');
    }
}

function button_off(but) {
    if (!but.classList.contains('button_dnw')) {
        but.classList.add('button_dnw');
    }
}

function mask_selection(event) {
    if (mask_elem.target) {
        mask_elem.target.classList.remove('add_border');
    }
    if (mask_elem.target == event.target) {
        mask_elem = set_default_mask_elem();
    } else {
        let
            output_width = document.getElementById('snake_width_volume'),
            output_height = document.getElementById('snake_height_volume');
        event.target.classList.add('add_border');
        mask_elem = {
            target: event.target,
            x: 0,
            y: 0,
            width: +output_width.value,
            height: +output_height.value,
        };
    }
    if (my_stream) {
        button_activation(document.getElementById('del_photo'));
        button_activation(document.getElementById('capture'));
    } else {
        if (!global_error) {
            button_activation(document.getElementById('download_photo'));
        }
    }
}


document.getElementById('del_photo').onclick = function() {
    context.clearRect(0, 0, canvas_width, canvas_height);
    if (mask_elem.target) {
        mask_elem.target.classList.remove('add_border');
        mask_elem = set_default_mask_elem();
    }
    main_photo = video;
    is_cam = false;
    button_activation(document.getElementById('capture'));
    button_activation(document.getElementById('del_photo'));
    button_activation(document.getElementById('download_photo'));
    cam_on();
}
