/*
base url : http://127.0.0.1:5500/public/index.html/product/productId
what we need : product/productId
what we going to turn it into : /server/routes.php?query0=product&query1=productId
excpected to be called on every GET

update for adding filters : 

*/

const BASE_URL = "public/";
function extract_link(){
    let url = window.location.pathname;
    let filter_json = window.location.search.substring(1);

    let sub_url = url.substring(url.indexOf(BASE_URL) + BASE_URL.length);
    let sub_url_arr = sub_url.split('/');

    let get_url = '/imperium/server/routes.php?';
    
    if (sub_url_arr[0] == '') {
        return get_url + filter_json;
    }
    
    for (let i = 0; i < sub_url_arr.length; i++) {
        get_url = get_url + 'query' + i + '=' + sub_url_arr[i];
        if (sub_url_arr.length - i != 1){ //if we are NOT at the last word
            get_url = get_url + '&';
        }
    }
    
    return get_url + '&' + filter_json;
}

function create_page_loader(){
    return '<div class="page-loader"><img src="assets/icons/fan-circled-svgrepo-com.svg" alt="loader"></div>';
}
async function getData() {
    const url = extract_link();
    
    try {
        const response = await fetch(url);
        if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
        }

        //add page loader animation
        document.querySelector('.content').innerHTML = create_page_loader();
        
        const data = await response.text();
        
        //reseting the carousels manually :(
        CAROUSEL_ONE = null;
        CAROUSEL_TWO = null;

        //add data do main page
        document.querySelector('.content').innerHTML = data;

    } catch (error) {
        console.error(error.message);
    }
}

window.onload = async function() {
    await getData();
};
window.addEventListener("popstate", async (event) => { await getData()});

async function goTo(url){
    history.pushState({tab: new URL(location).pathname}, "", url);
    await getData();
    window.scrollTo({top: "0", behavior: "smooth"});
}
async function eventGoTo(event, url){
    event.preventDefault();
    await goTo(url);
}

async function filterGoTo(page) {
    let sort = document.getElementById('sort').value;
    let order = document.getElementById('order').value;
    
    let min_price = document.getElementById('min').value;
    let max_price = document.getElementById('max').value;
    
    let filters = {
        'page': page ?? 1,
        'sort': sort,
        'order': order,
        'min_price': min_price,
        'max_price': max_price
    };

    let specific_filters = document.getElementsByClassName('radio_filter');
    for(let i = 0; i < specific_filters.length; i++){
        let checked_radio_input = document.querySelector('input[name="' +specific_filters[i].dataset.name +'"]:checked');
        if (checked_radio_input) {
            filters[specific_filters[i].dataset.name] = checked_radio_input.value;
        }
    }

    let encoded_filters = encodeURIComponent(JSON.stringify(filters));
    await goTo(window.location.pathname + '?filters='+ encoded_filters);
}

function pageBack(){
    let page = parseInt(document.getElementById('page_nr').innerHTML);
    if (page <= 1) {
        return;
    }
    page --;
    filterGoTo(page);
}
function pageForward(){
    let page = parseInt(document.getElementById('page_nr').innerHTML);
    page ++;
    filterGoTo(page);
}