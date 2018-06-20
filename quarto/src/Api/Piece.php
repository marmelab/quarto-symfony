<?php
namespace App\Api;

class Piece {

    private $id;
    private $used;

    public function __construct(int $id, bool $used)
    {
        $this->id = $id;
        $this->used = $used;
    }

    public function getId() : int {
        return $this->id;
    } 

    public function getUsed() : bool {
        return $this->used;
    } 

    public function setId(int $id) {
        $this->id = $id;
        return $this;
    } 

    public function setUsed(bool $used) {
        $this->used = $used;
        return $this;
    }

    public static function isWinningLine(array $pieces) : bool {
        if (!in_array('.', $pieces) && count($pieces) == 4) {
            $piece1 = $pieces[0];
            $piece2 = $pieces[1];
            $piece3 = $pieces[2];
            $piece4 = $pieces[3];

            $positiveCommonCarac = (($piece1 - 1) & ($piece2 - 1) & ($piece3 - 1) & ($piece4 - 1)) != 0;
            $negativeCommonCarac = (((($piece1 - 1) ^ 15) & (($piece2 - 1) ^ 15) & (($piece3 - 1) ^ 15) & (($piece4 - 1) ^ 15)) != 0);
            
            return $positiveCommonCarac || $negativeCommonCarac;
        }
        return false;
    }
}
