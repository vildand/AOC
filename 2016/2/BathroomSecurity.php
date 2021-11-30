<?php

(new BathroomSecurity('input.txt'))->getResult();

class BathroomSecurity
{
  private $input = '';
  private $x = 1;
  private $y = 1;
  private $map1 = ['02'=>'1','12'=>'2','22'=>'3','01'=>'4','11'=>'5','21'=>'6','00'=>'7','10'=>'8','20'=>'9'];
  private $map2 = [
    '4' => ['0'=>0,'1'=>0,'2'=>'1','3'=>0,'4'=>0],
    '3' => ['0'=>0,'1'=>'2','2'=>'3','3'=>'4','4'=>0],
    '2' => ['0'=>'5','1'=>'6','2'=>'7','3'=>'8','4'=>'9'],
    '1' => ['0'=>0,'1'=>'A','2'=>'B','3'=>'C','4'=>0],
    '0' => ['0'=>0,'1'=>0,'2'=>'D','3'=>0,'4'=>0]
  ];
  private $answer1 = '';
  private $answer2 = '';

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
  }

  public function getResult()
  {
    $aCoordinates = explode( "\n", $this->input);
    foreach ($aCoordinates as $line) {
      foreach (str_split($line) as $direction) {
        switch ($direction) {
          case 'U':
            $this->y += ($this->y+1==3?0:1);
            break;
          case 'R':
            $this->x += ($this->x+1==3?0:1);
            break;
          case 'D':
            $this->y -= ($this->y-1==-1?0:1);
            break;
          case 'L':
            $this->x -= ($this->x-1==-1?0:1);
            break;
        }
      }
      $this->answer1 .= $this->map1[$this->x.$this->y];
    }
    echo "Answer1 = ".$this->answer1."\n";
    $this->getResult2();
  }

  public function getResult2()
  {
    $this->x = 0;
    $this->y = 2;
    $aCoordinates = explode( "\n", $this->input);
    foreach ($aCoordinates as $line) {
      foreach (str_split($line) as $direction) {
        switch ($direction) {
          case 'U':
            $this->y += ($this->y+1==5 || @$this->map2[$this->y+1][$this->x] === 0 ? 0:1);
            break;
          case 'R':
            $this->x += ($this->x+1==5 || @$this->map2[$this->y][$this->x+1] === 0 ? 0:1);
            break;
          case 'D':
            $this->y -= ($this->y-1==-1 || @$this->map2[$this->y-1][$this->x] === 0 ? 0:1);
            break;
          case 'L':
            $this->x -= ($this->x-1==-1 || @$this->map2[$this->y][$this->x-1] === 0 ? 0:1);
            break;
        }
      }
      $this->answer2 .= $this->map2[$this->y][$this->x];
    }
    echo "\nAnswer2 = ".$this->answer2."\n";
  }
}