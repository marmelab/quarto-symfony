<?php
namespace App\Api;


class Piece {

    public $id;
    public $used;

    public function __construct(int $id, bool $used)
    {
        $this->id = $id;
        $this->used = $used;
    }
}