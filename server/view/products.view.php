<?php

function display_products($items){
echo <<<HTML
        <div class="main">
            <div class="filter-panel" id="filter_panel">
                <div class="filter-header">
                    <h3>FILTER BY :</h3>
                    <button class="button-link small-ham-button" onclick="show_filters()"><img src="assets/icons/hamburger-menu-svgrepo-com.svg" alt="filters" width="32px" height="32px"></button>
                </div>  
                <!--Same for evry category-->
                <fieldset class="price">
                    <legend>Price (ALL)</legend>
                    <label for="min">Min Price</label>
                    <div class="price-item">
                        <input type="number" id="min" value="0" onchange="update_value('number'); filterGoTo()">
                        <input type="range" id="min-range" value="0" onchange="update_value('range'); filterGoTo()">
                    </div>

                    <label for="max">Max Price</label>
                    <div class="price-item">
                        <input type="number" id="max" value="200000" onchange="update_value('number'); filterGoTo()">
                        <input type="range" id="max-range" value="100" onchange="update_value('range'); filterGoTo()">
                    </div>
                    <button class="filter-button" onclick="filterGoTo()">Filter</button>
                </fieldset>

                <!--Changes for different categories-->
                <fieldset class="radio_filter" data-name="filter-choice" onchange="filterGoTo()">
                    <legend>Filter name</legend>
                    <label><input type="radio" name="filter-choice" id="option_1" value="1"> Option 1</label>
                    <label><input type="radio" name="filter-choice" id="option_2" value="2"> Option 2</label>
                    <label><input type="radio" name="filter-choice" id="option_3" value="3"> Option 3</label>
                    <label><input type="radio" name="filter-choice" id="option_4" value="4"> Option 4</label>
                </fieldset>
                
                <!--Changes for different categories-->
                <fieldset class="radio_filter" data-name="filter-choice-second" onchange="filterGoTo()">
                    <legend>Filter name</legend>
                    <label><input type="radio" name="filter-choice-second" id="option_1" value="1"> Option 1</label>
                    <label><input type="radio" name="filter-choice-second" id="option_2" value="2"> Option 2</label>
                    <label><input type="radio" name="filter-choice-second" id="option_3" value="3"> Option 3</label>
                    <label><input type="radio" name="filter-choice-second" id="option_4" value="4"> Option 4</label>
                </fieldset>
            </div>

            <div class="content-panel">

                <div class="sorting-header">
                    <div class="left">
                        <h3>SORT :</h3>
                        <button class="button-link small-ham-button" id="small_ham_button" onclick="show_filters()"><img src="assets/icons/hamburger-menu-svgrepo-com.svg" alt="filters" width="32px" height="32px"></button>
                    </div>
                    <div class="right">
                        <label for="sort">Sort by : </label>
                        <select name="sort" id="sort" onchange="filterGoTo()">
                            <option value="popularity">Popularity 🔥</option>
                            <option value="date">Date 📅</option>
                            <option value="price">Price 🏷️</option>
                            <option value="rating">Rating ⭐</option>
                        </select>
                        <label for="order">Order : </label>
                        <select name="order" id="order" onchange="filterGoTo()">
                            <option value="descending">Descending ▼</option>
                            <option value="ascending">Ascending ▲</option>
                        </select>
                    </div>
                </div>

                <div class="cards displace-hide animate-in">
HTML;           
            if($items){
                foreach($items as $index=>$item){
                    $url = substr($item['image_url'], 16);//imperium/public/
                    $name = $item['name'];
                    if(strlen($item['name']) > 47) $name = substr($item['name'],0 , 47 - strlen($item['name'])) . "...";
                    echo <<<ITEM
                    <div class="card-item">
                        <a href="/imperium/public/{$item['category']}s/{$item['id']}" onclick="eventGoTo(event, '/imperium/public/{$item['category']}s/{$item['id']}')">
                            <div class="image">
                                <img src="{$url}" alt="{$name}">
                            </div>
                            <div class="description">
                                <div class="title">{$name}</div>
                                <span class="rating">
                    ITEM;
                                for($i = 0; $i < round($item['rating']); $i++){
                                    echo <<<ITEM
                                    <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                                    ITEM;
                                }
                        echo <<<ITEM
                                    <span class="votes"> ({$item['votes']})</span>
                                </span>
                                <div class="price-section">
                                    <div class="original-price"><span class="currency">€ </span><span class="number">{$item['original_price']}</span></div>
                                    <div class="price"> <span class="currency">€ </span><span class="number">{$item['price']}</span></div>
                                </div>
                            </div>
                        </a>
                        <div class="button-group">
                            <button id="cart" onclick="addToCart({$item['id']})">
                                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4.46785 10.2658C4.47574 10.3372 4.48376 10.4094 4.49187 10.4823L4.61751 11.6131C4.7057 12.4072 4.78218 13.0959 4.91562 13.6455C5.05917 14.2367 5.29582 14.7937 5.78931 15.2354C6.28281 15.6771 6.86251 15.8508 7.46598 15.9281C8.02694 16.0001 8.71985 16 9.51887 16H14.8723C15.4201 16 15.9036 16 16.3073 15.959C16.7448 15.9146 17.1698 15.8162 17.5785 15.5701C17.9872 15.324 18.2731 14.9944 18.5171 14.6286C18.7422 14.291 18.9684 13.8637 19.2246 13.3797L21.7141 8.67734C22.5974 7.00887 21.3879 4.99998 19.5 4.99998L9.39884 4.99998C8.41604 4.99993 7.57525 4.99988 6.90973 5.09287C6.5729 5.13994 6.24284 5.21529 5.93326 5.34375L5.78941 4.04912C5.65979 2.88255 4.67375 2 3.5 2H3C2.44772 2 2 2.44771 2 3C2 3.55228 2.44772 4 3 4H3.5C3.65465 4 3.78456 4.11628 3.80164 4.26998L4.46785 10.2658Z" fill="currentColor"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M14 19.5C14 18.1193 15.1193 17 16.5 17C17.8807 17 19 18.1193 19 19.5C19 20.8807 17.8807 22 16.5 22C15.1193 22 14 20.8807 14 19.5Z" fill="currentColor"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M5 19.5C5 18.1193 6.11929 17 7.5 17C8.88071 17 10 18.1193 10 19.5C10 20.8807 8.88071 22 7.5 22C6.11929 22 5 20.8807 5 19.5Z" fill="currentColor"></path> </g></svg>
                                <span>Cart</span>
                            </button>
                            <button id="save">
                                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M2 9.1371C2 14 6.01943 16.5914 8.96173 18.9109C10 19.7294 11 20.5 12 20.5C13 20.5 14 19.7294 15.0383 18.9109C17.9806 16.5914 22 14 22 9.1371C22 4.27416 16.4998 0.825464 12 5.50063C7.50016 0.825464 2 4.27416 2 9.1371Z" fill="currentColor"></path> </g></svg>
                                <span>Save</span>
                            </button>
                        </div>
                    </div>
                    ITEM;
                }
            }
echo <<<HTML

                </div>

                <div class="page-controls">
                    <div class="button-group">
                        <button class="circle-button">
                            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M14.9991 19L9.83911 14C9.56672 13.7429 9.34974 13.433 9.20142 13.0891C9.0531 12.7452 8.97656 12.3745 8.97656 12C8.97656 11.6255 9.0531 11.2548 9.20142 10.9109C9.34974 10.567 9.56672 10.2571 9.83911 10L14.9991 5" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </button>
                        <span id="page_nr">1</span>
                        <button class="circle-button">
                            <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 5L14.15 10C14.4237 10.2563 14.6419 10.5659 14.791 10.9099C14.9402 11.2539 15.0171 11.625 15.0171 12C15.0171 12.375 14.9402 12.7458 14.791 13.0898C14.6419 13.4339 14.4237 13.7437 14.15 14L9 19" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
HTML;
}