<?php

namespace Models;

/*
 * column_name      type            comment
 * -----------------------------------------
 * id               INT
 * code             VARCHAR(20)
 * type             VARCHAR(20)     table|grid
 * alias            VARCHAR(50)
 * description      TEXT
 * is_reply         TINYINT(1)
 * is_public        TINYINT(1)
 * is_editable      TINYINT(1)
 * is_deleted      TINYINT(1)
 * created_at       DATETIME
 * updated_at       DATETIME
 */

class BoardModel extends BaseModel
{
    protected $table = 'board';
    protected $allowedFields = [
        'id',
        'code',
        'type',
        'alias',
        'description',
        'is_reply',
        'is_public',
        'is_editable',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function initialize(): void
    {
        $this->createIfNotExist(['code' => 'popup'], [
            "code" => "popup",
            "type" => "static",
            "alias" => "팝업",
            "description" => "글을 작성하시면 메인 페이지에서 팝업으로 나타납니다. 글자는 표시되지 않습니다.",
            "is_editable" => "0",
            "is_reply" => "0",
            "is_deletable" => "0",
        ]);
        $this->createIfNotExist(['code' => 'menu'], [
            "code" => "menu",
            "type" => "grid",
            "alias" => "메뉴",
            "description" => "메뉴 및 메인 표시용 게시판",
            "is_editable" => "0",
            "is_reply" => "0",
            "is_deletable" => "0",
        ]);
        $this->createIfNotExist(['code' => 'notice'], [
            "code" => "notice",
            "type" => "table",
            "alias" => "공지사항",
            "description" => "공지사항 및 메인 표시용 게시판",
            "is_editable" => "0",
            "is_reply" => "0",
            "is_deletable" => "0",
        ]);
    }
}
