<?php

/*

1 kick 1
2 kick 2
3 snare 1
4 snare 2
5 closed hh
6 bass
7 melody 1
8 melody 2
9 melody 3
10 open hh
11 backing
12 noise 1
13 noise 2
14 noise 3
15 noise 4
16 noise 5
17 silence

position
sound
multiplier

pad
grid
pattern

120 bpm x 3 minutes = 360 Steps / 4 steps in a measure = 90 Measures
135 bpm x 3 minutes = 405 Steps / 4 steps in a measure = 101.25 Measures

*/

// I don't like this multi class setup. This should be a single class that takes an array of pads, bpm, and length and returns a pattern

$pad = new Pad();
$grid = new Grid();
$pattern = new Pattern();

$pad_1 = $pad->createPad(1, 1, 4);
$custom_grid = $grid->addPad($pad_1);

$pad_2 = $pad->createPad(2, 2, 4);
$custom_grid = $grid->addPad($pad_2);

$pad_3 = $pad->createPad(3, 3, 2);
$custom_grid = $grid->addPad($pad_3);

$pad_4 = $pad->createPad(4, 4, 2);
$custom_grid = $grid->addPad($pad_4);

$pad_5 = $pad->createPad(5, 5, 1);
$custom_grid = $grid->addPad($pad_5);

$pad_6 = $pad->createPad(6, 6, 1);
$custom_grid = $grid->addPad($pad_6);

$pad_7 = $pad->createPad(7, 7, 1);
$custom_grid = $grid->addPad($pad_7);

$pad_8 = $pad->createPad(8, 8, 1);
$custom_grid = $grid->addPad($pad_8);

$pad_9 = $pad->createPad(9, 9, 1);
$custom_grid = $grid->addPad($pad_9);

$pad_10 = $pad->createPad(10, 10, 1);
$custom_grid = $grid->addPad($pad_10);

$pad_11 = $pad->createPad(11, 11, 1);
$custom_grid = $grid->addPad($pad_11);

$pad_12 = $pad->createPad(12, 12, 1);
$custom_grid = $grid->addPad($pad_12);

$pad_13 = $pad->createPad(13, 13, 1);
$custom_grid = $grid->addPad($pad_13);

$pad_14 = $pad->createPad(14, 14, 1);
$custom_grid = $grid->addPad($pad_14);

$pad_15 = $pad->createPad(15, 15, 1);
$custom_grid = $grid->addPad($pad_15);

$pad_16 = $pad->createPad(16, 16, 1);
$custom_grid = $grid->addPad($pad_16);

// Silence pad
$pad_17 = $pad->createPad(17, 17, 8);
$custom_grid = $grid->addPad($pad_17);

$completed_grid = $grid->convertPads($custom_grid);

$bpm = 135;
$length_in_minutes = 3;

$final_pattern = $pattern->generatePattern($completed_grid, $bpm, $length_in_minutes);

print '<h1>PadGen</h1>';
print '<pre>';
print_r($final_pattern);
print '</pre>';



// A pad is a single instance of a pad with a sound assigned to it.
// The number of times this pad appears in the virtual grid is indicated by multiplier.
// The position on the grid indicates the note that must be triggered to play this pad.
// A 17th pad must be added which includes silence
class Pad {

  // Create the pad
  public function createPad($position = 1, $sound = 17, $multiplier = 1) {

    // Initialize the pad array
    $pad = array();

    // Populate the pad with properties
    $pad = array(
      'position' => $position,
      'sound' => $sound,
      'multiplier' => $multiplier
    );

    // Return the populated pad array
    return $pad;
  }

}

// A series of pads makes up a grid. A grid requires an array of 17 pads to be created.
class Grid {

  // Initialize the pads array
  public $pads = array();

  // Initialize the grid array
  public $grid = array();

  // Add another pad to the grid
  public function addPad($pad) {

    // If we already have 17 pads we need to reject this pad
    if (count($this->pads) >= 17) {
      // return the pads array without the new pad
      return $this->pads;
    }
    else {
      // Add the pad
      $this->pads[] = $pad;

      // Return the pads array with the new pad added
      return $this->pads;
    }
  }

  // Convert a filled array of 16 pads into an appropriate grid based on multipliers
  public function convertPads($pads) {

    // Loop over each of our pads
    foreach($pads as $pad) {
      // Load the multiplier for the current pad
      $multiplier = $pad['multiplier'];

      // Add the current pad to the grid multiple times if the multiplier calls for it
      for($i = 1; $i <= $multiplier; $i++) {
        $this->grid[] = $pad;
      }
    }

    // Return the populated grid
    return $this->grid;
  }

}

// A pattern is based on a given grid of 17 pads
// The length of the pattern is calculated with a passed in bpm
class Pattern {

  public function generatePattern($grid, $bpm, $length_in_minutes) {
    // Calculate the number of steps in our pattern
    $steps = $bpm * $length_in_minutes;
    // Calculate the number of measures in our pattern (always 4/4 time signature)
    $measures = $steps / 4;

    // Get the grid size
    $grid_size = count($grid) - 1;

    // Initialize the pattern array to hold our return array
    $pattern = array();

    // Loop over the number of steps
    for ($i = 1; $i <= $steps; $i++) {
      // Randomly select a pad to play based on the grid size
      $pad_to_play = $this->roll(1, $grid_size);
      // Assign the randomly selected pad to the pattern position
      $pattern[] = $grid[$pad_to_play];
    }

    // Return our populated pattern
    return $pattern;
  }

  // dice roller
  public function roll($num = 1, $sides = 2) {
    // Handle incorrect dice or sides
    if ($num == 0) { // Is the quantity zero?
      // There must be at least one die
      $num = 1;
    }
    if ($sides == 0) { // Is the quantity zero?
      // Any die must have at least one side
      $sides = 1;
    }

    // Initialize our return value
    $result = 0;

    // Loop over the die rolls
    for ($i=0; $i < $num; $i++) {
      // Roll the die
      $roll_result = mt_rand(1, $sides);

      // Add the die roll to our total result
      $result = $result + $roll_result;
    }

    // Return the result of our roll
    return $result;
  }

}

?>