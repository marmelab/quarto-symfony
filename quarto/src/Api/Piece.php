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
    } 

    public function setUsed(bool $used) {
        $this->used = $used;
    } 

    
}
