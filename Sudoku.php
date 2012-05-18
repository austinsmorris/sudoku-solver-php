<?php


class Sudoku {

    private $digits;
    private $rows;
    private $cols;
    private $squares;
    private $unitlist;
    private $units;
    private $peers;
    private $nassigns;
    private $neliminations;
    private $nsearches;

    function __construct() {

        $this->rows = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
        $this->cols = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
        $this->digits = "123456789";
        $this->squares = $this->cross($this->rows, $this->cols);

        $this->nassigns = 0;
        $this->neliminations = 0;
        $this->nsearches = 0;

        $this->unitlist = array();
        $rrows = array(array('A', 'B', 'C'), array('D', 'E', 'F'), array('G', 'H', 'I'));
        $ccols = array(array('1', '2', '3'), array('4', '5', '6'), array('7', '8', '9'));
        for ($i = 0; $i < sizeof($this->cols); $i++) {
            array_push($this->unitlist, $this->cross($this->rows, $this->cols[$i]));
        }
        for ($i = 0; $i < sizeof($this->rows); $i++) {
            array_push($this->unitlist, $this->cross($this->rows[$i], $this->cols));
        }
        for ($i = 0; $i < sizeof($rrows); $i++) {
            for ($j = 0; $j < sizeof($ccols); $j++) {
                array_push($this->unitlist, $this->cross($rrows[$i], $ccols[$j]));
            }
        }

        $this->units = array();
        for ($i = 0; $i < sizeof($this->squares); $i++) {
            $this->units[$this->squares[$i]] = array();
            for ($j = 0; $j < sizeof($this->unitlist); $j++) {
                if (in_array($this->squares[$i], $this->unitlist[$j])) {
                    array_push($this->units[$this->squares[$i]], $this->unitlist[$j]);
                }
            }
        }

        $this->peers = array();
        for ($i = 0; $i < sizeof($this->squares); $i++) {
            $this->peers[$this->squares[$i]] = array();
            for ($j = 0; $j < sizeof($this->units[$this->squares[$i]]); $j++) {
                $ul = $this->units[$this->squares[$i]][$j];
                for ($k = 0; $k < sizeof($ul); $k++) {
                    if ($ul[$k] != $this->squares[$i]) {
                        $this->peers[$this->squares[$i]][$ul[$k]] = true;
                    }
                }
            }
        }
    }


    private function assign(&$values, $sq, $dig) {
        $this->nassigns++;
        $result = true;
        $vals = $values[$sq];
        for ($i = 0; $i < strlen($vals); $i++) {
            if ($vals{$i} != $dig) {
                $result = $result & ($this->eliminate($values, $sq, $vals{$i}) ? true : false);
            }
        }
        return ($result ? $values : false);
    }

    private function cross($A, $B) {
        $C = array();
        for ($i = 0; $i < sizeof($A); $i++) {
            for ($j = 0; $j < sizeof($B); $j++) {
                array_push($C, $A[$i] . $B[$j]);
            }
        }
        return $C;
    }

    private function eliminate(&$values, $sq, $dig) {
        $this->neliminations++;

        if (empty($dig) OR strpos($values[$sq], $dig) === false) {
            return $values;
        }

        $values[$sq] = str_replace($dig, "", $values[$sq]);
        if (strlen($values[$sq]) == 0) {
            return false;
        }
        elseif (strlen($values[$sq]) == 1) {
            $result = true;
            foreach ($this->peers[$sq] as $s => $foo) {
                $result = $result & ($this->eliminate($values, $s, $values[$sq]) ? true : false);
            }
            if (!$result) {
                return false;
            }
        }

        for ($i = 0; $i < sizeof($this->units[$sq]); $i++) {
            $dplaces = array();
            for ($j = 0; $j < sizeof($this->units[$sq][$i]); $j++) {
                $sq2 = $this->units[$sq][$i][$j];
                if (strpos($values[$sq2], $dig) !== false) {
                    array_push($dplaces, $sq2);
                }
            }
            if (sizeof($dplaces) == 0) {
                return false;
            }
            elseif (sizeof($dplaces) == 1) {
                if (!$this->assign($values, $dplaces[0], $dig)) {
                    return false;
                }
            }
        }

        return $values;
    }

    private function parse_grid($grid) {
        $this->nassigns = 0;
        $this->neliminations = 0;
        $this->nsearches = 0;

        $grid2 = "";
        for ($i = 0; $i < strlen($grid); $i++) {
            if (strpos("0_.-123456789", $grid{$i}) !== false) {
                $grid2 .= $grid{$i};
            }
        }
        $values = array();
        for ($i = 0; $i < sizeof($this->squares); $i++) {
            $values[$this->squares[$i]] = $this->digits;
        }
        for ($i = 0; $i < sizeof($this->squares); $i++) {
            if (strpos($this->digits, $grid2{$i}) !== false AND !$this->assign($values, $this->squares[$i], $grid2{$i})) {
                return false;
            }
        }
        return $values;
    }


    private function search($values) {
        $this->nsearches++;

        if (!$values) {
            return false;
        }

        $min = 10;
        $max = 1;
        $sq = NULL;

        for ($i = 0; $i < sizeof($this->squares); $i++) {
            if (strlen($values[$this->squares[$i]]) > $max) {
                $max = strlen($values[$this->squares[$i]]);
            }
            if (strlen($values[$this->squares[$i]]) > 1 AND strlen($values[$this->squares[$i]]) < $min) {
                $min = strlen($values[$this->squares[$i]]);
                $sq = $this->squares[$i];
            }
        }

        if ($max == 1) {
            return $values;
        }

        for ($i = 0; $i < strlen($values[$sq]); $i++) {
            $test = $values;
            $res = $this->search($this->assign($test, $sq, $values[$sq]{$i}));
            if ($res) {
                return $res;
            }
        }

        return false;
    }

    public function solve($puzzle) {
        $resolution = "";

        $values = $this->search($this->parse_grid($puzzle));
        foreach($values as $sq) {
            $resolution .= $sq;
        }
        return $resolution;
    }

}
