//slider values for the filters
const MAX_PRICE = 2000;
const MIN_PRICE = 0;

function update_value(changed_element){
    let max_range = document.getElementById('max-range');
    let min_range = document.getElementById('min-range');
    let min_number = document.getElementById('min');
    let max_number = document.getElementById('max');
    
    switch (changed_element) {
        case 'range':
            if (parseInt(min_range.value) > parseInt(max_range.value)) {
                min_range.value = max_range.value;
            }
            //set the number
            let real = Math.floor((min_range.value / 100) * MAX_PRICE);
            min_number.value = real;

            if (parseInt(max_range.value) < parseInt(min_range.value)) {
                max_range.value = min_range.value;
            }
            real = Math.floor((parseInt(max_range.value) / 100) * MAX_PRICE);
            max_number.value = real;          
            break;
        case 'number':
            if (parseInt(min_number.value) > parseInt(max_number.value)) {
                min_number.value = max_number.value;
            }
            else if(parseInt(min_number.value) < MIN_PRICE){
                min_number.value = MIN_PRICE;
            }
            //set the number
            let real_number = Math.floor((parseInt(min_number.value) / MAX_PRICE) * 100);
            min_range.value = real_number;

            if (parseInt(max_number.value) < parseInt(min_number.value)) {
                max_number.value = min_number.value;
            }
            else if(parseInt(max_number.value) > MAX_PRICE){
                max_number.value = MAX_PRICE;
            }
            real_number = Math.floor((parseInt(max_number.value) / MAX_PRICE) * 100);
            max_range.value = real_number;
            break;
    }
}

function show_filters(){
    let panel = document.getElementById('filter_panel');
    if(panel && panel.classList.contains('show-filters')){
        panel.classList.remove('show-filters');
    }
    else if (panel && !panel.classList.contains('show-filters')){
        panel.classList.add('show-filters');
    }
}
function hide_filters(event){
    let panel = document.getElementById('filter_panel');
    if(panel && panel.classList.contains('show-filters') && !panel.contains(event.target) && !document.getElementById('small_ham_button').contains(event.target)){
        panel.classList.remove('show-filters');
    }
}
window.addEventListener( 'click', (event)=>{
    hide_filters(event);
});