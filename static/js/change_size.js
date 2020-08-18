function update_width(val) {
    let
        output = document.getElementById('snake_width_volume');
    val = +val;
    output.value = val;
    mask_elem.width = val;
}

function update_height(val) {
    let
        output = document.getElementById('snake_height_volume');
    val = +val;
    output.value = val;
    mask_elem.height = val;
}