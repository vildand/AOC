<?php
/*
 * --- Day 4: Giant Squid ---
You're already almost 1.5km (almost a mile) below the surface of the ocean, already so deep that you can't see any sunlight.
What you can see, however, is a giant squid that has attached itself to the outside of your submarine.

Maybe it wants to play bingo?

Bingo is played on a set of boards each consisting of a 5x5 grid of numbers.
Numbers are chosen at random, and the chosen number is marked on all boards on which it appears. (Numbers may not appear on all boards.)
If all numbers in any row or any column of a board are marked, that board wins. (Diagonals don't count.)

The submarine has a bingo subsystem to help passengers (currently, you and the giant squid) pass the time.
It automatically generates a random order in which to draw numbers and a random set of boards (your puzzle input). For example:

7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1

22 13 17 11  0
 8  2 23  4 24
21  9 14 16  7
 6 10  3 18  5
 1 12 20 15 19

 3 15  0  2 22
 9 18 13 17  5
19  8  7 25 23
20 11 10 24  4
14 21 16 12  6

14 21 17 24  4
10 16 15  9 19
18  8 23 26 20
22 11 13  6  5
 2  0 12  3  7
After the first five numbers are drawn (7, 4, 9, 5, and 11), there are no winners, but the boards are marked as follows (shown here adjacent to each other to save space):

22 13 17 11  0         3 15  0  2 22        14 21 17 24  4
 8  2 23  4 24         9 18 13 17  5        10 16 15  9 19
21  9 14 16  7        19  8  7 25 23        18  8 23 26 20
 6 10  3 18  5        20 11 10 24  4        22 11 13  6  5
 1 12 20 15 19        14 21 16 12  6         2  0 12  3  7
After the next six numbers are drawn (17, 23, 2, 0, 14, and 21), there are still no winners:

22 13 17 11  0         3 15  0  2 22        14 21 17 24  4
 8  2 23  4 24         9 18 13 17  5        10 16 15  9 19
21  9 14 16  7        19  8  7 25 23        18  8 23 26 20
 6 10  3 18  5        20 11 10 24  4        22 11 13  6  5
 1 12 20 15 19        14 21 16 12  6         2  0 12  3  7
Finally, 24 is drawn:

22 13 17 11  0         3 15  0  2 22        14 21 17 24  4
 8  2 23  4 24         9 18 13 17  5        10 16 15  9 19
21  9 14 16  7        19  8  7 25 23        18  8 23 26 20
 6 10  3 18  5        20 11 10 24  4        22 11 13  6  5
 1 12 20 15 19        14 21 16 12  6         2  0 12  3  7
At this point, the third board wins because it has at least one complete row or column of marked numbers (in this case, the entire top row is marked: 14 21 17 24 4).

The score of the winning board can now be calculated. Start by finding the sum of all unmarked numbers on that board; in this case, the sum is 188. Then, multiply that sum by the number that was just called when the board won, 24, to get the final score, 188 * 24 = 4512.

To guarantee victory against the giant squid, figure out which board will win first. What will your final score be if you choose that board?


 */

declare(strict_types = 1);

require_once '../../Lib/Lib.php';

new GiantSquid('input.txt');
class GiantSquid
{
  private string $input;
  private array $drawnList;
  private array $boards;

  public function __construct(string $filename)
  {
    $this->input = file_get_contents($filename);
    echo "Answer1 = ".$this->getAnswer1()."\n";
    echo "Answer2 = ".$this->getAnswer2()."\n";
  }

  public function getAnswer1()
  {
    $this->loadData();
    foreach ($this->drawnList as $number) {
      foreach ($this->boards as $board) {
        $board->setNumber($number);
        if ($board->hasBingo()) {
          return ($board->getUnmarkedSum() * $number);
        }
      }
    }
  }

  public function getAnswer2()
  {
    $this->loadData();
    foreach ($this->drawnList as $number) {
      foreach ($this->boards as $key => $board) {
        $board->setNumber($number);
        if ($board->hasBingo()) {
          if (count($this->boards) === 1) {
            return ($board->getUnmarkedSum() * $number);
          } else {
            unset($this->boards[$key]);
          }
        }
      }
    }
  }

  private function loadData()
  {
    $this->boards = [];
    $arr = explode( "\n", $this->input);
    $this->drawnList = explode(',',$arr[0]);
    $board = [];
    for ($x=2;$x<count($arr);$x++) {
      if (empty($arr[$x])) {
        $this->boards[] = new BingoBoard($board);
        $board = [];
      } else {
        $board[] = [
          intval(substr($arr[$x],0,2)),
          intval(substr($arr[$x],3,2)),
          intval(substr($arr[$x],6,2)),
          intval(substr($arr[$x],9,2)),
          intval(substr($arr[$x],12,2)),
        ];
      }
    }
  }
}

class BingoBoard
{
  private array $board;

  public function __construct(array $aBoard)
  {
    $aNewBoard = [];
    foreach ($aBoard as $aRow) {
      $aNewRow = [];
      for ($x=0;$x<count($aRow);$x++) {
        $aNewRow[$x] = (new BingoField($aRow[$x]));
      }
      $aNewBoard[] = $aNewRow;
    }
    $this->board = $aNewBoard;
  }

  public function setNumber(string $number)
  {
    foreach ($this->board as $row){
      foreach ($row as $field){
        if($field->getValue() === intval($number)){
          $field->setMarked();
        }
      }
    }
  }

  public function hasBingo():bool
  {
    return $this->checkBoard($this->board)
      || $this->checkBoard(LIB::array_rotate($this->board));
  }

  private function checkBoard(array $board):bool {
    foreach ($board as $row){
      $bingo = true;
      foreach ($row as $field){
        if ($field->getMarked() === false){
          $bingo = false;
        }
      }
      if ($bingo) {
        return true;
      }
    }
    return false;
  }

  public function getUnmarkedSum():int
  {
    $sum = 0;
    foreach ($this->board as $row){
      foreach ($row as $field){
        if ($field->getMarked() === false){
          $sum += $field->getValue();
        }
      }
    }
    return $sum;
  }
}

class BingoField
{
  private int $value;
  private bool $marked;

  public function __construct(int $value, bool $marked=false)
  {
    $this->value=$value;
    $this->marked=$marked;
  }
  public function getValue():int {
    return $this->value;
  }
  public function getMarked():bool {
    return $this->marked;
  }
  public function setMarked(bool $marked=true) {
    $this->marked=$marked;
  }
}