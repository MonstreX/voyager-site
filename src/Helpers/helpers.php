<?php


// Makes Multi Level TREE (array) from FLAT array (adds Children elements)
if (!function_exists('flatToTree'))
{
    function flatToTree($flat_array)
    {
        $result = $flat_array;
        $result = buildTree($result);
        return $result;
    }
}

// For flatToTree($flat_array)
if (!function_exists('buildTree'))
{
    function buildTree(array $elements, $parentId = null, $sort = true)
    {
        $branch = array();

        foreach ($elements as $element)
        {
            if ($element['parent_id'] == $parentId) {
                $children = buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        if($sort) {
            usort($branch, function ($item1, $item2) {
                return $item1['order'] > $item2['order'];
            });
        }

        return $branch;
    }
}

// Makes FLAT Sorted Array from Multi level Sorted array TREE (excludes children elements)
if (!function_exists('buildFlatFromTree')) {
    function buildFlatFromTree($tree)
    {
        $result = [];
        $level = 0;

        buildFlatChildren($tree, $result, $level);

        return $result;
    }
}

// buildFlatFromTree($tree)
if (!function_exists('buildFlatChildren')) {
    function buildFlatChildren($children, &$result, &$level)
    {
        foreach ($children as $child) {
            $elements = [];
            foreach ($child as $key => $field) {
                if($key !== 'children') {
                    $elements[$key] = $field;
                    $elements['level'] = $level;
                }
            }
            $result[] = $elements;
            if (isset($child['children'])) {
                $level++;
                buildFlatChildren($child['children'], $result, $level);
                $level--;
            }
        }
    }
}

if (!function_exists('translit_ru')) {
    function translit_ru($string) {
        $charlist = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_"
        );
        return strtr($string,$charlist);
    }
}
