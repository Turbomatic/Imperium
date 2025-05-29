<?php
require 'model/Product.php';

function getHomeProducts($filters)
{
    require '../config/db.php';
    $pro = new Product($conn);

    //Best selling products
    $sellingCount = 5;
    if ($filters && array_key_exists('bestSelling', $filters)) $sellingCount = $filters['bestSelling'];
    $filtersQuery = " ORDER BY orders DESC LIMIT " . $sellingCount;
    $queryResultSelling = $pro->getProductsByFilters('', $filtersQuery);

    //New products
    $newCount = 5;
    if ($filters && array_key_exists('newProducts', $filters)) $newCount = $filters['newProducts'];
    $filtersQuery = " ORDER BY created_at DESC LIMIT " . $newCount;
    $queryResultNew = $pro->getProductsByFilters('', $filtersQuery);

    return [
        'bestSelling' => $queryResultSelling,
        'newProducts' => $queryResultNew
    ];
}

function getProducts($filters)
{
    require '../config/db.php';
    $pro = new Product($conn);

    $filtersQuery = "";
    $joinQuery = "";

    //Category filter
    if ($filters && array_key_exists('category', $filters)) $filtersQuery = addFilter($filtersQuery, "category = " . "'" . $filters['category'] . "'");

    //Subcategory filter
    if ($filters && array_key_exists('subcategory', $filters)) {
        $filtersQuery = addFilter($filtersQuery, "sub_category = " . "'" . $filters['subcategory'] . "'");
        $tableName = $pro->getTableName($filters['subcategory']);
        if ($tableName) $joinQuery = " JOIN " . $tableName . " ON id = product_id";

        foreach ($filters as $key => $value) {
            if (
                $key != 'category' && $key != 'subcategory' &&
                $key != 'min_price' && $key != 'max_price' &&
                $key != 'sort' && $key != 'order' &&
                $key != 'page'
            ) {
                $filtersQuery = addFilter($filtersQuery, $key . " = " . "'" . $value . "'");
            }
        }
    }

    //Price filter
    if ($filters && array_key_exists('min_price', $filters)) $filtersQuery = addFilter($filtersQuery, "price >= " . $filters['min_price']);
    if ($filters && array_key_exists('max_price', $filters)) $filtersQuery = addFilter($filtersQuery, "price <= " . $filters['max_price']);

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
        if (array_key_exists('order', $filters) && $filters['order'] == "ascending") $order = " ASC";
        $filtersQuery = $filtersQuery . " ORDER BY " . $sort . $order;
    }

    //Page product limit
    $limit = 50;
    $offset = 0;
    if ($filters && array_key_exists('page', $filters)) $offset = 50 * ($filters['page'] - 1);
    $filtersQuery = $filtersQuery . " LIMIT " . $limit . " OFFSET " . $offset;

    //Running query
    $queryResult = $pro->getProductsByFilters($joinQuery, $filtersQuery);
    if (!$queryResult) {
        return [
            'products' => null,
            'newFilters' => null,
            'oldFilters' => $filters,
        ];
    } else {
        $newFilters = getNewFilters($filters);
        return ['products' =>$queryResult, 'newFilters' => $newFilters, 'oldFilters'=>$filters];
    }

    $conn = null;
}

function addFilter($filtersQuery, $filter)
{
    if (strlen($filtersQuery) > 0) return $filtersQuery . " && " . $filter;
    return $filtersQuery . " WHERE " . $filter;
}

function getNewFilters($filters)
{
    $newFilters = [];
    if (array_key_exists('category', $filters)) {
        switch ($filters['category']) {
            case 'component':
                $newFilters['subcategory'] = ['cpu', 'gpu', 'psu', 'motherboard', 'ram'];
                break;
            case 'peripheral':
                $newFilters['subcategory'] = ['monitor', 'keyboard', 'mouse', 'speaker'];
                break;
            case 'prebuilt':
                $newFilters['ram'] = ['8GB DDR4', '32GB DDR5', '64GB DDR5', '128GB DDR5'];
                $newFilters['storage'] = ['500GB NVMe SSD', '1TB NVMe SSD', '2TB NVMe PCIe Gen4 SSD', '4TB NVMe PCIe Gen4 SSD'];
                break;
            case 'deal':
                $newFilters['subcategory'] = ['motherboard', 'storage'];
                break;
        }
    }

    if (array_key_exists('subcategory', $filters)) {
        switch ($filters['subcategory']) {
            case 'cpu':
                $newFilters['cores'] = [4, 8, 12, 16, 24];
                $newFilters['socket'] = ['LGA1700', 'AM4', 'AM5'];
                break;
            case 'gpu':
                $newFilters['memory'] = ['8GB GDDR6', '12GB GDDR6', '16GB GDDR6', '24GB GDDR6X'];
                break;
            case 'psu':
                $newFilters['efficiency_rating'] = ['80 PLUS Bronze', '80 PLUS Gold', '80 PLUS Platinum'];
                $newFilters['modular'] = ['Yes', 'Semi'];
                break;
            case 'motherboard':
                $newFilters['chipset'] = ['AMD B550', 'AMD X670', 'Intel B760', 'Intel Z790'];
                $newFilters['ram_type'] = ['DDR4', 'DDR5'];
                $newFilters['max_ram'] = ['128GB', '192GB'];
                break;
            case 'ram':
                $newFilters['capacity'] = ['16GB', '32GB', '64GB'];
                $newFilters['type'] = ['DDR4', 'DDR5'];
                break;
            case 'monitor':
                $newFilters['screen_size'] = ['24 inches', '27 inches', '32 inches', '34 inches'];
                $newFilters['resolution'] = ['1920x1080', '2560x1440', '3440x1440'];
                $newFilters['refresh_rate'] = ['165Hz', '170Hz', '240Hz', '300Hz'];
                break;
            case 'keyboard':
                $newFilters['connection_type'] = ['Wired', 'Wireless'];
                break;
            case 'mouse':
                $newFilters['wireless'] = ['Yes', 'No'];
                $newFilters['buttons'] = [5, 6, 11];
                break;
            case 'speaker':
                $newFilters['wireless'] = ['Yes', 'No'];
                $newFilters['channels'] = ['2.0', '2.1', '7.1 Virtual'];
                break;
        }
    }

    return $newFilters;
}
