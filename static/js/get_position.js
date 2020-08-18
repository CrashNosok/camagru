let global_location = '';

async function success(pos) {
    let crd = pos.coords;
    let position = await fetch(`https://geocode-maps.yandex.ru/1.x/?apikey=59655e87-cf58-472d-9de7-1b4b70fd943a&lang=en_RU&format=json&geocode=${crd.longitude},${crd.latitude}`);
    let result = await position.json();
    // console.log(result.response.GeoObjectCollection.featureMember[1].GeoObject.description);
    let area = result.response.GeoObjectCollection.featureMember[1].GeoObject.description;
    if (area) {
        global_location = area;
    }
    // console.log(global_location);
};

function error(err) {
    // console.log('Error occurred. Error code: ' + error.code);
    // err_codes = {
    //     0: 'unknown error',
    //     1: 'permission denied',
    //     2: 'position unavailable (error response from location provider)',
    //     3: 'timed out',
    // }
    
    // console.log(`ERROR(${err.code}): ${err.message}`);
    return false;
};

async function get_position() {
    if (navigator.geolocation) {
        let options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };
        
        let area = await navigator.geolocation.getCurrentPosition(success, error, options);
        // console.log('location = ' + area);
        // console.log('location2 = ' + global_location);
    }
    // 'https://geocode-maps.yandex.ru/1.x/?apikey=59655e87-cf58-472d-9de7-1b4b70fd943a&lang=en_RU&format=json&geocode=56.696452,60.833025400000004'
    
}
