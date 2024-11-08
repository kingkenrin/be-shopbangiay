<?php
namespace Src\Util;

class formatRes{
    static function getData(array $fields = [], array $object = []) {
        return array_intersect_key($object, array_flip($fields));
    }
}