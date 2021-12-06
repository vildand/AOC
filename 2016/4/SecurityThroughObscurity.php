<?php

new SecurityThroughObscurity('input.txt');

class SecurityThroughObscurity
{
  private $input = '';

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
    echo $this->getAnswer1();
    //echo $this->getAnswer2();
  }

  public function getAnswer1():string
  {
    $iSum = 0;
    //$aLines[] = 'not-a-real-room-404[oarel]';
    $aLines = explode( "\n", $this->input);
    foreach ($aLines as $line) {

      //chunk
      $aStack = explode( "-", $line);

      //pop and isolate control values
      $matches = [];
      preg_match_all('#(\d+)\[([a-z]+)\]#', array_pop($aStack), $matches);
      $sControlNum = $matches[1][0];
      $sControlStr = $matches[2][0];

      //atomize and sort
      $stringParts = str_split(implode($aStack));
      sort($stringParts);

      //group by characters and sort by length
      $matches = [];
      preg_match_all('#([a-z])\1*#', implode($stringParts), $matches);
      usort($matches[0], function($a, $b) {
        return strlen($b) - strlen($a);
      });

      //rearrange to 2d array with primary keys being the length of subarrays
      $aaRes = [];
      foreach ($matches[0] as $match){
        $aaRes[strlen($match)][$match[0]] = $match[0];
      }

      $bRealRoom = true;
      foreach ($aaRes as $aRes) {
        while($aRes) {
          if (array_key_exists($sControlStr[0], $aRes)){
            unset ($aRes[$sControlStr[0]]);
            $sControlStr = substr($sControlStr, 1);
            if (!$sControlStr) {
              break;
            }
          } else {
            $bRealRoom = false;
            break;
          }
        }
        if (!$bRealRoom || !$sControlStr){
          break;
        }
      }
      if ($bRealRoom) {
        $iSum += intval($sControlNum);
      }
    }

    return "Answer1 = ".$iSum."\n";

  }

  public function getAnswer2()
  {

    $str = 'qqweq';
    $str = substr($str, 1);
    var_dump($str);

    /*$aLines = explode("\n", $this->input);
    foreach ($aLines as $line) {
    }
    return "Answer2 = " . "\n";*/
  }
}