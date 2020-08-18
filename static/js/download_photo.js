document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('download_photo').onclick = download_foo;
});

async function download_foo(event) {
    if (mask_elem.target) {
        let new_img = canvas.toDataURL('image/png');

        clearInterval(main_interval);
        draw_main_photo();
        
        let img = canvas.toDataURL('image/png').replace('data:image/png;base64,', '');

        main_interval = setInterval(inter, 30);

        let
            src = mask_elem.target.getAttribute('src');

        let info = {
            'upload_cam': img,
            'mask': src,
            'coord_x': mask_elem.x,
            'coord_y': mask_elem.y,
            'width': mask_elem.width,
            'height': mask_elem.height,
            'geolocation': global_location,
        };
        let response = await fetch('/camagru/upload_photo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: JSON.stringify(info),
        });
        let result = await response.text();
        location.reload();
    }
}
