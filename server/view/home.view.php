<?php
function display_home($best_selling, $new_items){    
echo <<<HTML
        <div class="hero">
            <div class="left">
                <div class="cto displace-hide animate-in">
                    <h1>Conquer Your Next Build</h1>
                    <h3>High-performance parts. Unmatched reliability. Built for victory.</h3>
                    <button class="cto-button">BUY NOW</button>
                </div>
            </div>

            <div class="right" id="hero-right" >
                <div class="carousel-wrapper">
                    <div class="carousel displace-hide animate-in">

                        <button class="carousel-item-wrapper pc_btn" disabled onclick="btn_pop_into_view(this, 0)">
                            <div class="carousel-item first-selected">
                                <img src="assets/pictures/pc-item.png" alt="prebuild pc">
                            </div>
                        </button>
                        <button class="carousel-item-wrapper pc_btn" onclick="btn_pop_into_view(this, 1)">
                            <div class="carousel-item">
                                <img src="assets/pictures/pc-item.png" alt="prebuild pc">
                            </div>
                        </button>
                        <button class="carousel-item-wrapper pc_btn" onclick="btn_pop_into_view(this, 2)">
                            <div class="carousel-item">
                                <img src="assets/pictures/pc-item.png" alt="prebuild pc">
                            </div>
                        </button>
                        <button class="carousel-item-wrapper pc_btn" onclick="btn_pop_into_view(this, 3)">
                            <div class="carousel-item">
                                <img src="assets/pictures/pc-item.png" alt="prebuild pc">
                            </div>
                        </button>
                        <button class="carousel-item-wrapper pc_btn" onclick="btn_pop_into_view(this, 4)">
                            <div class="carousel-item">
                                <img src="assets/pictures/pc-item.png" alt="prebuild pc">
                            </div>
                        </button>                        

                    </div>
                </div>
            </div>
        </div>

        
        <div class="categories displace-hide animate-in">
            <h2>Explore Categories</h2>
            <div class="category-wrapper">

                <div class="category-item">
                    <a href="/imperium/public/prebuilts" onclick="goToEvent(event, '/imperium/public/prebuilts')">
                        <div class="background" style="background-image: url(assets/pictures/pc-background.png);"></div>
                        <div class="foreground">
                            <span class="upper-bold">PREBUILT PCs</span>
                        </div>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/imperium/public/components" onclick="goToEvent(event, '/imperium/public/components')">
                        <div class="background" style="background-image: url(assets/pictures/components-background.png);"></div>
                        <div class="foreground">
                            <span class="upper-bold">COMPONENTS</span>
                        </div>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/imperium/public/peripherals" onclick="goToEvent(event, '/imperium/public/peripherals')">
                        <div class="background" style="background-image: url(assets/pictures/peripherals-background.png);"></div>
                        <div class="foreground">
                            <span class="upper-bold">PERIPHERALS</span>
                        </div>
                    </a>
                </div>
                <div class="category-item">
                    <a href="/imperium/public/deals" onclick="goToEvent(event, '/imperium/public/')">
                        <div class="background" style="background-image: url(assets/pictures/deals-background.png);"></div>
                        <div class="foreground">
                            <span class="upper-bold">DEALS</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>


        
        <h2>BEST SELLING PRODUCTS</h2>
        <div class="carousel-wrapper-basic displace-hide animate-in">
            <div class="carousel" id="first-carousel">
HTML;
                $i = 0;
                
                foreach ($best_selling as $index=>$item){
                    $url = substr($item['image_url'], 16);//imperium/public/
                    //$path = urlencode("?filters={'subcategory':" . $item['subcategory']."}"); // not sure if useful :(
                    echo <<<ITEM
                    <a href="/imperium/public/{$item['category']}s/{$item['id']}" eventGoTo(event, '/imperium/public/{$item['category']}s/{$item['id']}')>
                        <div class="carousel-item">
                            <h3>{$item['name']}</h3>
                            <img src="{$url}" alt="{$item['name']}">
                        </div>
                    </a>
                    ITEM;
                    $i++;
                }
echo <<<HTML
            </div>
            <button class="left button-link" onclick="carousel_one('right',0)"><img src="assets/icons/left-2-svgrepo-com.svg" width="30px" alt="left"></button>
            <button class="right button-link" onclick="carousel_one('left',0)"><img src="assets/icons/right-2-svgrepo-com.svg" width="30px" alt="right"></button>
            <div class="carousel-button-group">
                <ul id="first-carousel-buttons">
HTML;
                    $j = 0;
                    foreach ($best_selling as $index=>$item){
                        if ($j == 0){
                            echo <<<ITEM
                            <li><button onclick="carousel_one('move_to',{$j})" class="expand-button"><div class="fill-button"></div></button></li>
                            ITEM;
                        }
                        else{
                            echo <<<ITEM
                            <li><button onclick="carousel_one('move_to',{$j})"><div class="fill-button"></div></button></li>
                            ITEM;
                        }
                        $j++;
                    }
echo <<<HTML
                </ul>
            </div>
        </div>
        <h2>NEW PRODUCTS</h2>
        <div class="carousel-wrapper-basic  second-carousel-wrapper-basic displace-hide animate-in">
            <div class="carousel" id="second-carousel">
HTML;
                $i = 0;
                foreach ($new_items as $index=>$item){
                    $url = substr($item['image_url'], 16);//imperium/public/
                    //$path = urlencode("?filters={'subcategory':" . $item['subcategory']."}"); // not sure if useful :(
                    echo <<<ITEM
                    <a href="/imperium/public/{$item['category']}s/{$item['id']}" eventGoTo(event, '/imperium/public/{$item['category']}s/{$item['id']}')>
                        <div class="carousel-item">
                            <h3>{$item['name']}</h3>
                            <img src="{$url}" alt="{$item['name']}">
                        </div>
                    </a>
                    ITEM;
                    $i++;
                }
echo <<<HTML
            </div>
            <button class="left button-link" onclick="carousel_two('right',0)"><img src="assets/icons/left-2-svgrepo-com.svg" width="30px" alt="left"></button>
            <button class="right button-link" onclick="carousel_two('left',0)"><img src="assets/icons/right-2-svgrepo-com.svg" width="30px" alt="right"></button>
            <div class="carousel-button-group">
                <ul id="second-carousel-buttons">
HTML;
                    $j = 0;
                    foreach ($new_items as $index=>$item){
                        if ($j == 0){
                            echo <<<ITEM
                            <li><button onclick="carousel_two('move_to',{$j})" class="expand-button"><div class="fill-button"></div></button></li>
                            ITEM;
                        }
                        else{
                            echo <<<ITEM
                            <li><button onclick="carousel_two('move_to',{$j})"><div class="fill-button"></div></button></li>
                            ITEM;
                        }
                        $j++;
                    }
echo <<<HTML
                </ul>
            </div>
        </div>        


        <div class="selling-points-section displace-hide animate-in">
            <div class="left">
                <div class="left-wrapper">
                    <h1>WHAT WE OFFER</h1>
                </div>
            </div>

            <div class="right">
                <div class="right-wrapper">
                    <ul>
                        <li><img src="assets/icons/diamond-bullet-point.png" alt="bullet point">Unmatched Perfomance</li>
                        <li><img src="assets/icons/diamond-bullet-point.png" alt="bullet point">Transparent Pricing</li>
                        <li><img src="assets/icons/diamond-bullet-point.png" alt="bullet point">Expert Support</li>
                        <li><img src="assets/icons/diamond-bullet-point.png" alt="bullet point">Rock-Solid Security</li>
                        <li><img src="assets/icons/diamond-bullet-point.png" alt="bullet point">Future-Proof Technology</li>
                    </ul>
                </div>
            </div>
        </div>

        <h2>TESTIMONIALS</h2>
        <div class="testimonials displace-hide animate-in">
            <div class="testimonial-item">
                <div class="user-image">
                    <img src="assets/pictures/man_blue_shirt.png" alt="User">
                </div>
                <div class="text">
                    <div class="name">John Doe</div>
                    <span class="title">Costumer</span>
                    <span class="rating">
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                    </span>
                    <p>This is the best web-page for buying stuff i have ever seen. I am not lying and I am totally a real person.</p>
                </div>
            </div>
            <div class="testimonial-item">
                <div class="user-image">
                    <img src="assets/pictures/woman_red_shirt.png" alt="User">
                </div>
                <div class="text">
                    <div class="name">Janice Doe</div>
                    <span class="title">Costumer</span>
                    <span class="rating">
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                    </span>
                    <p>This is the best web-page for buying stuff i have ever seen. I am not lying and I am totally a real person.</p>
                </div>
            </div>
            <div class="testimonial-item">
                <div class="user-image">
                    <img src="assets/pictures/woman_red_shirt_white.png" alt="User">
                </div>
                <div class="text">
                    <div class="name">Joahne Doe</div>
                    <span class="title">Costumer</span>
                    <span class="rating">
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M541.44 103.68l126.72 256 282.88 42.24-204.8 198.4L794.88 883.2 541.44 750.08 288 883.2l48.64-282.88-204.8-198.4 282.88-42.24z" fill="#ebff66"></path><path d="M794.88 896c-2.56 0-3.84 0-6.4-1.28L541.44 764.16 294.4 894.72c-3.84 2.56-8.96 1.28-14.08-1.28-3.84-2.56-6.4-7.68-5.12-12.8l47.36-275.2L122.88 410.88c-3.84-3.84-5.12-8.96-3.84-12.8s5.12-7.68 10.24-8.96l276.48-39.68 124.16-250.88c3.84-8.96 19.2-8.96 23.04 0l124.16 250.88 276.48 39.68c5.12 1.28 8.96 3.84 10.24 8.96 1.28 5.12 0 10.24-3.84 12.8L760.32 605.44l47.36 275.2c1.28 5.12-1.28 10.24-5.12 12.8-2.56 1.28-5.12 2.56-7.68 2.56zM541.44 737.28c2.56 0 3.84 0 6.4 1.28l230.4 121.6-43.52-256c-1.28-3.84 1.28-8.96 3.84-11.52l185.6-181.76-257.28-37.12c-3.84 0-7.68-3.84-10.24-6.4l-115.2-232.96-115.2 232.96c-1.28 3.84-5.12 6.4-10.24 6.4l-257.28 37.12L345.6 591.36c2.56 2.56 3.84 7.68 3.84 11.52l-43.52 256 230.4-121.6h5.12z" fill="#231C1C"></path></g></svg>
                    </span>
                    <p>This is the best web-page for buying stuff i have ever seen. I am not lying and I am totally a real person.</p>
                </div>
            </div>
        </div>
HTML;
}