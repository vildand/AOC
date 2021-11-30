<?php

echo substr('abcdef', -100%6, 1);


(new EasterBunnyHeadquarters('input.txt'))->getResult();

class EasterBunnyHeadquarters
{
  private $input;
  private $x = 0;
  private $y = 0;
  private $compassOffset;
  const COMPASS = 'nesw';

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
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
    return substr(self::COMPASS,  $this->compassOffset%4, 1);
  }

  public function getResult()
  {
    $aCoordinates = explode(', ', $this->input);
    foreach ($aCoordinates as $coordinate) {
      $compass = $this->switchDir(substr($coordinate, 0, 1));
      $len = intval(substr($coordinate, 1));
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
    echo abs($this->x + $this->y);
  }
}