<?php

class SudokoSolver {
	
	private $grille = [];
	private $comming_arr = array();

	public function __construct()
	{
		$this->time_tracking['start'] = microtime(true);
	}

	private function isAbsentLine($value, $line)
	{
    	for ($j=0; $j<9; $j++) {
        	if ($value == $this->grille[$line][$j]) {
            	return false;
        	}
    	}

    	return true;
	}
	
	private function isAbsentColumn($value, $column)
	{
	    for ($i=0; $i<9; $i++) {
	        if ($value == $this->grille[$i][$column]) {
	            return false;
	        }
	    }
	
	    return true;
	}

	private function isAbsentBloc($value, $line, $column)
	{
	    $kline = 3*intval($line/3);
	    $kcolumn = 3*intval($column/3);
	    $klineLimit = $kline+3;
	    $kcolumnLimit = $kcolumn +3;
	
	    for($i=$kline; $i < $kline+3; $i++) {
	        for ($j=$kcolumn; $j < $kcolumnLimit; $j++) {
	            if ($value == $this->grille[$i][$j]) {
	                return false;
	            }
	        }
	    }
	
	    return true;
	}

	public function getPossibilitiesCell($index_line, $index_column)
	{
		$values = [];

		for ($i=1; $i<=9; $i++) {
			if ($this->isAbsentBloc($i, $index_line, $index_column) && $this->isAbsentLine($i, $index_line) && $this->isAbsentColumn($i, $index_column)) {
				$values[] = $i;
			}
		}

		shuffle($values);

		return $values;
	}

	/**
	 * Function to display the grid
	 *
	 *
	 */
	public function getResult()
	{
		echo "\n";

        foreach ($this->grille as $row) {
            foreach ($row as $value) {
                echo $value . ' ';
            }

            echo "\n";
        }
	}

	/**
	 * Function to check the values of the grid
	 *
	 *
	 */
	public function checkGridValues()
	{
		for ($i=0; $i < 9; $i++) {
			for ($j=0; $j < 9; $j++) {
				if ($this->grille[$i][$j] > 9 || $this->grille[$i][$j] < 0) {
					
					echo 'Grid value or values error';

					return false;	
				}
			}
		}

		return true;
	}

	/**
	 * Function to solve the grid
	 *
	 *
	 */
	public function solve(array $array)
	{	
		while(true) {
			$this->grille = $array;

			//fill cell of possibilities
			$allCellpossibilities = [];

			for ($i=0; $i < 9; $i++) {
				for ($j=0; $j < 9; $j++) {
					
					if (0 == $array[$i][$j]) {

						$allCellpossibilities[] = array(
        	                    'rowIndex' 	  => $i,
        	                    'columnIndex' => $j,
        	                    'permissible' => $this->getPossibilitiesCell($i, $j)
        	                );
					}
				}
			}

			if (empty($allCellpossibilities)) {
				return $array;
			}

			//trier le tableau des possibilites
	 		usort($allCellpossibilities, array($this, 'cmp')); 
			
			if (count($allCellpossibilities[0]['permissible']) == 1) {
                $array[$allCellpossibilities[0]['rowIndex']][$allCellpossibilities[0]['columnIndex']] = current($allCellpossibilities[0]['permissible']);
                continue;
            }

			//affecter la valeur à la cellule 
			foreach ($allCellpossibilities[0]['permissible'] as $value) {
				$tmp = $array;
				$tmp[$allCellpossibilities[0]['rowIndex']][$allCellpossibilities[0]['columnIndex']] = $value;
				
				if ($result = $this->solve($tmp)) {
					return $this->solve($tmp);
				}
			}

			return false;
		}
	}

	public function cmp(array $a, array $b) 
	{
		$nbA = count($a['permissible']);
		$nbB = count($b['permissible']);

		if ($nbA == $nbB) {
			return 0;
		}
		return ($nbA < $nbB) ? -1 : 1;
	}

	/*public function __destruct()
	{    
        $this->time_tracking['end'] = microtime(true);
        $time = $this->time_tracking['end'] - $this->time_tracking['start'];
        echo "\nExecution time : " . number_format($time, 3) . " sec\n\n";
    }*/

    /**
     *
     *
     */
    public function getResultArray()
    {
    	return $this->grille;
    }

    public function getTimeExecution()
    {
    	$this->time_tracking['end'] = microtime(true);
        $time = $this->time_tracking['end'] - $this->time_tracking['start'];
        
        return number_format($time, 3);
    }
}
