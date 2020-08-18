function set_default_mask_elem() {
    return {
        target: null,
        x: 0,
        y: 0,
        width: 000,
        height: 000,
    };
}

// for slider
let
    slides = document.querySelectorAll('.slide_single'),
    slider = [],
    step = 0, 
    box_size = 100,
    count_masc = 3;

// for cam
let
    video = document.getElementById('video'),
    my_stream = null,
    main_photo = document.getElementById('tmp_photo'),
    canvas_width = 600,
    canvas_height = 500,
    canvas = document.getElementById('canvas'),
    context = canvas.getContext('2d'),
    is_cam = false,
    mask_elem = set_default_mask_elem();
    selected = false,
    global_error = false,
    mouse = {
        win_x: 0,
        win_y: 0,
        x: 0,
        y: 0,
        down: false,
    };

if (+document.body.clientWidth <= 769) {
    canvas_width = 300;
    canvas_height = 200;
}

get_position();

let c = document.getElementById('canvas');
c.setAttribute('width', canvas_width);
c.setAttribute('height', canvas_height);
