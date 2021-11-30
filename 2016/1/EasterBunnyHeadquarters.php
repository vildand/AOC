<?php

(new EasterBunnyHeadquarters('input.txt'))->getResult();

class EasterBunnyHeadquarters
{
  private $input = '';
  private $x = 0;
  private $y = 0;
  private $map = [];
  private $compassOffset = 0;
  private $found = false;
  const COMPASS = 'nesw';

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
  }

  public function getResult()
  {
    $aCoordinates = explode(', ', $this->input);
    foreach ($aCoordinates as $coordinate) {
      $compass = $this->switchDir(substr($coordinate, 0, 1));
      $len = intval(substr($coordinate, 1));
      $this->findHeadquarter($compass, $len);
      switch ($compass) {
        case 'n':
          $this->y += $len;
          break;
        case 'e':
          $this->x += $len;
          break;
        case 's':
          $this->y -= $len;
          break;
        case 'w':
          $this->x -= $len;
          break;
      }

    }
    echo "\nAnswer1 = ".abs($this->x + $this->y)."\n";
  }

  private function switchDir($dir): string
  {
    switch ($dir) {
      case 'R':
        $this->compassOffset++;
        break;
      case 'L':
        $this->compassOffset--;
        break;
      default:
        throw new \Exception("WRONG DIRECTION !!!");
    }
    return substr(self::COMPASS,  $this->compassOffset%strlen(self::COMPASS), 1);
  }

  private function findHeadquarter($compass, $len)
  {
    for ($c=0;$c<$len;$c++){
      switch ($compass) {
        case 'n':
          $this->map($this->x.'+'.($this->y+$c));
          break;
        case 'e':
          $this->map(($this->x+$c).'+'.$this->y);
          break;
        case 's':
          $this->map($this->x.'+'.($this->y-$c));
          break;
        case 'w':
          $this->map(($this->x-$c).'+'.$this->y);
          break;
      }
    }
  }

  private function map(string $key) {
    if (isset ($this->map[$key]) && $this->found === false) {
      $aSum = explode('+', $key);
      echo 'Answer2 = ' . abs(intval($aSum[0])+intval($aSum[1]));
      $this->found = true;
    } else {
      $this->map[$key] = 1;
    }
  }

}