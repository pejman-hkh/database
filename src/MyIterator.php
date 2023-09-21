<?php
namespace Pejman\Database;
class MyIterator implements \Iterator {
    private $position = 0;
    public function append( $array ) {
        $this->array = $array;
    }

    public function __construct() {
        $this->position = 0;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    #[\ReturnTypeWillChange]
    public function current() {
        return $this->array[$this->position];
    }

    #[\ReturnTypeWillChange]
    public function key() {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function valid(): bool {
        return isset($this->array[$this->position]);
    }
}