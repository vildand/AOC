<?php

class Lib
{
  static function array_rotate(array $matrix): array
  {
    array_unshift($matrix, null);
    $matrix = call_user_func_array('array_map', $matrix);
    return array_map('array_reverse', $matrix);
  }

  static function array_remove_empty(array $arr): array
  {
    return array_filter($arr, 'strlen');
  }

}