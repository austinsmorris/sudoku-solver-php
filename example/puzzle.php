<?php

// $puzzle = "003020600900305001001806400008102900700000008006708200002609500800203009005010300";
// $puzzle = "4.....8.5.3..........7......2.....6.....8.4......1.......6.3.7.5..2.....1.4......";
// $puzzle = "3...8.......7....51..............36...2..4....7...........6.13..452...........8..";
// $puzzle = "6.....7.3.4.8.................5.4.8.7..2.....1.3.......2.....5.....7.9......1....";
// $puzzle = "963......1....8......2.5....4.8......1....7......3..257......3...9.2.4.7......9..";
// $puzzle = "..7..8.....6.2.3...3......9.1..5..6.....1.....7.9....2........4.83..4...26....51.";
$puzzle = "1.....3.8.6.4..............2.3.1...........958.........5.6...7.....8.2...4.......";
$sudoku = new Sudoku();
echo $sudoku->solve($puzzle);