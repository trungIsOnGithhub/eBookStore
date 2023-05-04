class MinimumFallingPathSum {

    /**
     * @param Integer[][] $matrix
     * @return Integer
     */
    private function validIndex($x, $y, $n) {
        return $x >= 0 && $y >= 0 && $x < $n && $y < $n;
    }
    private function recursiveExplore($rowIdx, &$dp, &$matrix) {
        $n = count($matrix);

        if($rowIdx == $n-1) {
            return; // final row
        }

        for($i = 0; $i < $n; $i++) {
            $row = $rowIdx;
            $col = $i;

            if( $this->validIndex($row+1, $col-1, $n) ) {
                $dp[$row+1][$col-1] = min($dp[$row+1][$col-1], $dp[$row][$col] + $matrix[$row+1][$col-1]);
            }
            if( $this->validIndex($row+1, $col, $n) ) {
                $dp[$row+1][$col] = min($dp[$row+1][$col], $dp[$row][$col] + $matrix[$row+1][$col]);
            }
            if( $this->validIndex($row+1, $col+1, $n) ) {
                $dp[$row+1][$col+1] = min($dp[$row+1][$col+1], $dp[$row][$col] + $matrix[$row+1][$col+1]);
            }
        }

        $this->recursiveExplore($rowIdx+1, $dp, $matrix);
    }
    function solve($matrix) {
        $n = count($matrix);

        // $minPathSum  = 999999;

        $dp = array_fill(0, $n, array_fill(0, $n, 999999));

        for($i = 0; $i < $n; $i++) {
            $dp[0][$i] = $matrix[0][$i];
        }

        $this->recursiveExplore(0, $dp, $matrix);

        return min($dp[$n-1]);
    }
}
