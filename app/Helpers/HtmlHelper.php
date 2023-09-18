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
            $pagination = $input['pagination'];
            if (isset($pagination['page']) &&
                isset($pagination['per-page']) &&
                isset($pagination['total']) &&
                isset($pagination['total-page'])) {
                $data['pagination'] = [
                    'page' => $pagination['page'],
                    'per-page' => $pagination['per-page'],
                    'total' => $pagination['total'],
                    'total-page' => $pagination['total-page'],
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

    public static function showDataEmpty($data): bool
    {
        if (!isset($data) || is_array($data) && sizeof($data) == 0) {
            self::showMessage('err_empty_folder', 'No data available.');
            return false;
        }
        return true;
    }

    public static function showMessage($icon, $text): void
    {
        echo '<div class="no-data-box">
                <div class="no-data-wrap">
                    <img src="/asset/images/icon/' . $icon . '.png">
                    <span>' . $text . '</span>
                </div>
            </div>';
    }
}
