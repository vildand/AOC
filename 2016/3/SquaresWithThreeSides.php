<?php

new SquaresWithThreeSides('input.txt');

class SquaresWithThreeSides
{
  private $input = '';

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
    echo $this->getAnswer1();
    echo $this->getAnswer2();
  }

  public function getAnswer1():string
  {
    $aLines = explode( "\n", $this->input);
    foreach ($aLines as $line) {
      $aCoordinates = array_values(array_filter(explode( '  ', trim($line))));
      $polygon = new Polygon(trim($aCoordinates[0]),trim($aCoordinates[1]),trim($aCoordinates[2]));
      if ($polygon->isTriangle()) {
        $triangles[] = $polygon;
      }
    }
    return "Answer1 = ".count($triangles)."\n";
  }

  public function getAnswer2():string
  {
    $aLines = explode( "\n", $this->input);
    foreach ($aLines as $line) {
      $aInput[] = array_values(array_filter(explode( '  ', trim($line))));
    }
    array_unshift($aInput, null);
    $aInput = call_user_func_array('array_map', $aInput);
    $aInput = array_map('array_reverse', $aInput);
    foreach ($aInput as $aColumn) {
      $aCoordinates = array_chunk($aColumn, 3);
      foreach ($aCoordinates as $aCoordinate) {
        $polygon = new Polygon(trim($aCoordinate[0]),trim($aCoordinate[1]),trim($aCoordinate[2]));
        if ($polygon->isTriangle()) {
          $triangles[] = $polygon;
        }
      }
    }
    return "Answer2 = ".count($triangles)."\n";
  }
}

class Polygon {

  private $a,$b,$c;
  public function __construct($a, $b, $c)
  {
    $this->a = $a;
    $this->b = $b;
    $this->c = $c;
  }

  public function isTriangle()
  {
    return
      $this->a + $this->b > $this->c &&
      $this->a + $this->c > $this->b &&
      $this->b + $this->c > $this->a;
  }
}