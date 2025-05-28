<?php
require '../model/Product.php';

function getHomeProducts($filters) {
    require '../../config/db.php';
    $pro = new Product($conn);

    //Best selling products
    $sellingCount = 5;
    if ($filters && array_key_exists('bestSelling', $filters)) $sellingCount = $filters['bestSelling'];
    $filtersQuery = " ORDER BY orders DESC LIMIT ".$sellingCount;
    $queryResultSelling = $pro->getProductsByFilters('', $filtersQuery);

    //New products
    $newCount = 5;
    if ($filters && array_key_exists('newProducts', $filters)) $newCount = $filters['newProducts'];
    $filtersQuery = " ORDER BY created_at DESC LIMIT ".$newCount;
    $queryResultNew = $pro->getProductsByFilters('', $filtersQuery);

    return [
        'bestSelling' => $queryResultSelling,
        'newProducts' => $queryResultNew
    ];
}

function getProducts($filters) {
    require '../../config/db.php';
    $pro = new Product($conn);

    $filtersQuery = "";
    $joinQuery = "";

    //Category filter
    if ($filters && array_key_exists('category', $filters)) $filtersQuery = addFilter($filtersQuery, "category = " . "'".$filters['category']."'");
    
    //Subcategory filter
    if ($filters && array_key_exists('subcategory', $filters)) {
        $filtersQuery = addFilter($filtersQuery, "sub_category = " . "'".$filters['subcategory']."'");
        $tableName = $pro->getTableName($filters['subcategory']);
        if ($tableName) $joinQuery = " JOIN ". $tableName ." ON id = product_id";

        foreach ($filters as $key => $value) {
            if (
                $key != 'category' && $key != 'subcategory' &&
                $key != 'minPrice' && $key != 'maxPrice' &&
                $key != 'sort' && $key != 'order' &&
                $key != 'page'
            ) {
                $filtersQuery = addFilter($filtersQuery, $key . " = " . "'".$value."'");
            }
        }
    }

    //Price filter
    if ($filters && array_key_exists('minPrice', $filters)) $filtersQuery = addFilter($filtersQuery, "price >= ".$filters['minPrice']);
    if ($filters && array_key_exists('maxPrice', $filters)) $filtersQuery = addFilter($filtersQuery, "price <= ".$filters['maxPrice']);

    //Sorting and ordering
    if ($filters && array_key_exists('sort', $filters)) {
        $sort = 'created_at';
        switch ($filters['sort']) {
            case 'date':
                $sort = 'created_at';
                break;
            case 'price':
                $sort = 'price';
                break;
            case 'popularity':
                $sort = 'orders';
                break;
            case 'rating':
                $sort = 'rating';
        }
        $order = " DESC";
        if (array_key_exists('order', $filters) && $filters['order'] == "Ascending") $order = " ASC";
        $filtersQuery = $filtersQuery . " ORDER BY " .$sort .$order;
    }

    //Page product limit
    $limit = 50;
    $offset = 0;
    if ($filters && array_key_exists('page', $filters)) $offset = 50 * ($filters['page'] - 1);
    $filtersQuery = $filtersQuery . " LIMIT " .$limit. " OFFSET " .$offset;
    
    //Running query
    echo $filtersQuery . "<br>";
    $queryResult = $pro->getProductsByFilters($joinQuery, $filtersQuery);
    $newFilters = getNewFilters($filters);
    echo json_encode($newFilters) . "<br>";
    echo json_encode($queryResult);
    if (!$queryResult) {
        return [
            'products' => null,
            'newFilters' => null
        ];
    } else {
        $newFilters = getNewFilters($filters);
        return [
            'products' => $queryResult,
            'newFilters' => $newFilters
        ];
    }
    
    $conn = null;
}

function addFilter($filtersQuery, $filter) {
    if (strlen($filtersQuery) > 0) return $filtersQuery . " && " . $filter;
    return $filtersQuery . " WHERE " . $filter;
}

function getNewFilters($filters) {
    $newFilters = [];
    if (array_key_exists('category', $filters)) {
        switch ($filters['category']) {
            case 'component':
                array_push($newFilters ,[
                    'subcategory' => ['cpu', 'gpu', 'psu', 'motherboard', 'ram']
                ]);
                break;
            case 'peripheral':
                array_push($newFilters ,[
                    'subcategory' => ['monitor', 'keyboard', 'mouse', 'speaker']
                ]);
                break;
            case 'prebuilt':
                array_push($newFilters ,[
                    'ram' => ['8GB DDR4', '32GB DDR5', '64GB DDR5', '128GB DDR5'],
                    'storage' => ['500GB NVMe SSD', '1TB NVMe SSD', '2TB NVMe PCIe Gen4 SSD', '4TB NVMe PCIe Gen4 SSD'],
                ]);
                break;
            case 'deal':
                array_push($newFilters ,[
                    'subcategory' => ['motherboard', 'storage']
                ]);
                break;
        }
    }

    if (array_key_exists('subcategory', $filters)) {
        switch ($filters['subcategory']) {
            case 'cpu':
                array_push($newFilters ,[
                    'cores' => [4, 8, 12, 16, 24],
                    'socket' => ['LGA1700', 'AM4', 'AM5']
                ]);
                break;
            case 'gpu':
                array_push($newFilters ,[
                    'memory' => ['8GB GDDR6', '12GB GDDR6', '16GB GDDR6', '24GB GDDR6X'],
                ]);
                break;
            case 'psu':
                array_push($newFilters ,[
                    'efficency_rating' => ['80 PLUS Bronze', '80 PLUS Gold', '80 PLUS Platinum'],
                    'modular' => ['Yes', 'Semi']
                ]);
                break;
            case 'motherboard':
                array_push($newFilters ,[
                    'chipset' => ['AMD B550', 'AMD X670', 'Intel B760', 'Intel Z790'],
                    'ram_type' => ['DDR4', 'DDR5'],
                    'max_ram' => ['128GB', '192GB']
                ]);
                break;
            case 'ram':
                array_push($newFilters ,[
                    'capacity' => ['16GB', '32GB', '64GB', 16, 24],
                    'type' => ['DDR4', 'DDR5'],
                ]);
                break;
            case 'monitor':
                array_push($newFilters ,[
                    'screen_size' => ['24 inches', '27 inches', '32 inches', '34 inches'],
                    'resolution' => ['1920x1080', '2560x1440', '3440x1440'],
                    'refresh_rate' => ['165Hz', '170Hz', '240Hz', '300Hz'],
                ]);
                break;
            case 'keyboard':
                array_push($newFilters ,[
                    'connection_type' => ['Wired', 'Wireless']
                ]);
                break;
            case 'mouse':
                array_push($newFilters ,[
                    'wireless' => ['Yes', 'No'],
                    'buttons' => [5, 6, 11]
                ]);
                break;
            case 'speaker':
                array_push($newFilters ,[
                    'wireless' => ['Yes', 'No'],
                    'channels' => ['2.0', '2.1', '7.1 Virtual']
                ]);
                break;
        }
    }

    return $newFilters;
}