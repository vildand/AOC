<?php

new SecurityThroughObscurity('input.txt');

class SecurityThroughObscurity
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
    $iSum = 0;
    $aLines = explode( "\n", $this->input);
    foreach ($aLines as $line) {

      //chunk
      $aStack = explode( "-", $line);

      //pop and isolate control values
      $matches = [];
      preg_match_all('#(\d+)\[([a-z]+)\]#', array_pop($aStack), $matches);
      $iControlNum = intval($matches[1][0]);
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

      //match and filter
      $bRealRoom = true;
      foreach ($aaRes as $aRes) {
        while($aRes) {
          if (array_key_exists($sControlStr[0], $aRes)){
            unset ($aRes[$sControlStr[0]]);
            $sControlStr = substr($sControlStr, 1);
            if (!$sControlStr) break;
          } else {
            $bRealRoom = false;
            break;
          }
        }
        if (!$bRealRoom || !$sControlStr) break;
      }
      $iSum += $bRealRoom ? $iControlNum:0;
    }
    return "Answer1 = ".$iSum."\n";
  }

  public function getAnswer2()
  {
    $seq = 'abcdefghijklmnopqrstuvwxyz';
    $len = strlen($seq);
    $aLines = explode("\n", $this->input);

    foreach ($aLines as $line) {
      //chunk
      $aStack = explode( "-", $line);

      //pop and isolate control value
      $matches = [];
      preg_match_all('#(\d+)\[([a-z]+)\]#', array_pop($aStack), $matches);
      $iControlNum = intval($matches[1][0]);
      $iOffset = $iControlNum%$len;

      //atomize string
      $stringParts = str_split(implode(' ', $aStack));
      $aRes = [];
      //foreach rotate
      for($c=0;$c<count($stringParts);$c++) {
        if ($stringParts[$c] === ' ') {
          $aRes[] = ' ';
          continue;
        }
        $seqPos = strpos($seq, $stringParts[$c]);
        $newPos = ($seqPos+$iOffset >= $len) ? ($iOffset-($len-$seqPos)) : $seqPos+$iOffset;
        $aRes[$c]=substr($seq, $newPos, 1);
      }

      //concat string
      $sRes=implode($aRes);

      //break and echo result if found
      if (preg_match_all('#northpole#',$sRes, $matches)) break;

    }

    return "Answer2 = " . $iControlNum . "\n";

  }
}