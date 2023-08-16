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
            if (Utils::endsWith($pagination_link, '/')) {
                $pagination_link = substr_replace($pagination_link, '', -1);
            }
            $data['pagination_link'] = $pagination_link;
        }
        return View("pagination", $data);
    }

    public static function getReply($topic_id, $input): string
    {
        $data = [];
        $data['topic_id'] = $topic_id;
        if (isset($input)) {
            if (isset($input['page']) && isset($input['per-page']) && isset($input['total']) && isset($input['total-page'])) {
                $data['pagination'] = [
                    'page' => $input['page'],
                    'per-page' => $input['per-page'],
                    'total' => $input['total'],
                    'total-page' => $input['total-page'],
                ];
            }
            if (isset($input['array'])) {
                $data['array'] = $input['array'];
            }
        }
        return View("reply", $data);
    }

    public static function covertTextarea($string)
    {
        return str_replace('\n', '&#10;', $string);
    }
}
