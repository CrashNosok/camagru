document.addEventListener("DOMContentLoaded", () => {
    ready();
});

function cam_on() {
    let
        vendorUrl = window.URL || window.webkitURL;

    navigator.getMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.
    mozGetUserMedia || navigator.msGetUserMedia;
    navigator.getMedia({
        video: true,
        audio: false
    }, function(stream) {
        my_stream = stream;
        video.srcObject = stream;
        video.play();
    }, function(error) {
        alert('Ошибка! Что-то пошло не так, попробуйте позже.');
        global_error = true;
    });
}

function cam_off() {
    if (my_stream) {
        video.pause();
        my_stream.getVideoTracks()[0].stop();
        my_stream = null;
    }
}

function is_cursor_in_canvas() {
    return mouse.win_x > canvas.offsetLeft && mouse.win_x < canvas.offsetLeft + canvas_width &&
        mouse.win_y > canvas.offsetTop && mouse.win_y < canvas.offsetTop + canvas_height;
}

function ready() {
    cam_on();
    main_photo = video;
    is_cam = false;

    document.getElementById('capture').addEventListener('click', function() {

        context.drawImage(video, 0, 0, canvas_width, canvas_height);
        main_photo = context.getImageData(0, 0, canvas_width, canvas_height);
        is_cam = true;
        cam_off();
        button_off(document.getElementById('capture'));
        button_on(document.getElementById('download_photo'));
    });
};

function previewImage() {
    let file = document.getElementById('file').files;
    let filename = file[0].name.toLowerCase();
    if (!filename.endsWith('.jpeg') && !filename.endsWith('.jpg') && !filename.endsWith('.png')) {
        return;
    }
    context.clearRect(0, 0, canvas_width, canvas_height);
    if (file.length > 0) {
        let fileReader = new FileReader();

        fileReader.onload = function(event) {
            main_photo = document.getElementById('tmp_photo');
            is_cam = false;
            main_photo.setAttribute('src', window.URL.createObjectURL(file[0]));
        };
        fileReader.readAsDataURL(file[0]);
    }
    cam_off();
    button_on(document.getElementById('del_photo'));
    button_off(document.getElementById('capture'));
    if (mask_elem.target) {
        button_on(document.getElementById('download_photo'));
    }
}

function draw_main_photo() {
    context.clearRect(0, 0, canvas_width, canvas_height);
    if (is_cam) {
        context.putImageData(main_photo, 0, 0);
    } else {
        context.drawImage(main_photo, 0, 0, canvas_width, canvas_height);
    }  
}

function draw_masks() {
    let i = 0;
    if (mask_elem.target) {
        if (mask_elem.target.classList.contains('add_border')) {
            context.drawImage(mask_elem.target, mask_elem.x, mask_elem.y, mask_elem.width, mask_elem.height);
            if (is_cursor_in_mask()) {
                stroke_mask(mask_elem);
                if (mouse.down) {
                    selected = mask_elem;
                }
            }
        } else {
            mask_elem = set_default_mask_elem();
        }
        i++;
    }
}

function inter() {
    draw_main_photo();
    draw_masks();
    if (selected && mouse.down) {
        selected.x = mouse.x - selected.width / 2;
        selected.y = mouse.y - selected.height / 2;
    }
    if (!is_cursor_in_canvas()) {
        mouse.down = false;
        selected = false;
    }
}

let main_interval = setInterval(function() {
    inter();
}, 30);
