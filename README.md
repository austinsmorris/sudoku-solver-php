This is a sudoku solver class for php based on the solution by [Peter Norvig](http://norvig.com/sudoku.html).

To use the class, simply instantiate a Sudoku object:
```php
$sudoku = new Sudoku();
```

Then, just pass a puzzle string to the solve() method:
```php
$sudoku->solve($puzzle); //see $puzze format below
```

You can simply echo this output or manipulate the string for other formatting.

#### `$puzzle` description
The puzzle itself should be a string of initial values for each square read right to left, top to bottom, starting in
the upper left.  For blank squares, use a zero, period, underscore, or hyphen.  An example puzzlem implementation is
located in the puzzle.php file in the example folder.

Enjoy!
