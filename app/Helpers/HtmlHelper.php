<?php

namespace App\Helpers;

final class HtmlHelper
{
    public static function getPagination($pagination, $pagination_link): string
    {
        $data = [];
        if (isset($pagination)) {
            $data['pagination'] = $pagination;
        }
        if (isset($pagination_link)) {
            $data['pagination_link'] = $pagination_link;
        }
        return View("pagination", $data);
    }
}
