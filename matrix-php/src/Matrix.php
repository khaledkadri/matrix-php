<?php

namespace MatrixPHP;

use InvalidArgumentException;
use MatrixPHP\Exceptions\DimensionException;
use MatrixPHP\Exceptions\SingularMatrixException;

class Matrix
{
    private array $data;
    private int $rows;
    private int $cols;

    /**
     * Create a new Matrix instance
     *
     * @param array $data 2D array representing the matrix
     * @throws DimensionException if dimensions are invalid
     */
    public function __construct(array $data)
    {
        $this->validateData($data);
        $this->data = $data;
        $this->rows = count($data);
        $this->cols = count($data[0]);
    }

    /**
     * Get matrix element at position (i, j)
     *
     * @param int $i Row index
     * @param int $j Column index
     * @return mixed
     */
    public function get(int $i, int $j)
    {
        return $this->data[$i][$j];
    }

    /**
     * Set matrix element at position (i, j)
     *
     * @param int $i Row index
     * @param int $j Column index
     * @param mixed $value
     */
    public function set(int $i, int $j, $value): void
    {
        $this->data[$i][$j] = $value;
    }

    /**
     * Calculate matrix determinant
     *
     * @return float
     * @throws DimensionException if matrix is not square
     */
    public function determinant(): float
    {
        if ($this->rows !== $this->cols) {
            throw new DimensionException("Determinant requires square matrix");
        }

        // Base cases
        if ($this->rows === 1) {
            return $this->data[0][0];
        }

        if ($this->rows === 2) {
            return $this->data[0][0] * $this->data[1][1] 
                 - $this->data[0][1] * $this->data[1][0];
        }

        // Recursive calculation for larger matrices
        $det = 0;
        for ($j = 0; $j < $this->cols; $j++) {
            $sign = ($j % 2 === 0) ? 1 : -1;
            $subMatrix = $this->getSubMatrix(0, $j);
            $det += $sign * $this->data[0][$j] * $subMatrix->determinant();
        }

        return $det;
    }

    /**
     * Calculate matrix inverse
     *
     * @return Matrix
     * @throws SingularMatrixException if matrix is singular
     */
    public function inverse(): Matrix
    {
        $det = $this->determinant();
        
        if (abs($det) < 1e-10) {
            throw new SingularMatrixException("Matrix is singular (determinant = 0)");
        }

        $cofactorMatrix = $this->cofactor();
        $adjugate = $cofactorMatrix->transpose();
        
        return $adjugate->multiply(1 / $det);
    }

    /**
     * Calculate cofactor matrix
     *
     * @return Matrix
     */
    public function cofactor(): Matrix
    {
        $result = [];
        
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $sign = (($i + $j) % 2 === 0) ? 1 : -1;
                $subMatrix = $this->getSubMatrix($i, $j);
                $result[$i][$j] = $sign * $subMatrix->determinant();
            }
        }

        return new Matrix($result);
    }

    /**
     * Get submatrix by removing row i and column j
     *
     * @param int $excludeRow Row to exclude
     * @param int $excludeCol Column to exclude
     * @return Matrix
     */
    public function getSubMatrix(int $excludeRow, int $excludeCol): Matrix
    {
        $result = [];
        $resultRow = 0;

        for ($i = 0; $i < $this->rows; $i++) {
            if ($i === $excludeRow) continue;

            $resultCol = 0;
            for ($j = 0; $j < $this->cols; $j++) {
                if ($j === $excludeCol) continue;
                
                $result[$resultRow][$resultCol] = $this->data[$i][$j];
                $resultCol++;
            }
            $resultRow++;
        }

        return new Matrix($result);
    }

    /**
     * Transpose the matrix
     *
     * @return Matrix
     */
    public function transpose(): Matrix
    {
        $result = [];
        
        for ($j = 0; $j < $this->cols; $j++) {
            for ($i = 0; $i < $this->rows; $i++) {
                $result[$j][$i] = $this->data[$i][$j];
            }
        }

        return new Matrix($result);
    }

    /**
     * Multiply matrix by scalar
     *
     * @param float $scalar
     * @return Matrix
     */
    public function multiply(float $scalar): Matrix
    {
        $result = [];
        
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $result[$i][$j] = $this->data[$i][$j] * $scalar;
            }
        }

        return new Matrix($result);
    }

    /**
     * Add two matrices
     *
     * @param Matrix $B
     * @return Matrix
     * @throws DimensionException if dimensions don't match
     */
    public function add(Matrix $B): Matrix
    {
        if ($this->rows !== $B->rows || $this->cols !== $B->cols) {
            throw new DimensionException("Matrix dimensions must match for addition");
        }

        $result = [];
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $result[$i][$j] = $this->data[$i][$j] + $B->get($i, $j);
            }
        }

        return new Matrix($result);
    }

    /**
     * Subtract two matrices
     *
     * @param Matrix $B
     * @return Matrix
     * @throws DimensionException if dimensions don't match
     */
    public function sub(Matrix $B): Matrix
    {
        if ($this->rows !== $B->rows || $this->cols !== $B->cols) {
            throw new DimensionException("Matrix dimensions must match for subtraction");
        }

        $result = [];
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $result[$i][$j] = $this->data[$i][$j] - $B->get($i, $j);
            }
        }

        return new Matrix($result);
    }

    /**
     * Multiply two matrices
     *
     * @param Matrix $B
     * @return Matrix
     * @throws DimensionException if dimensions incompatible
     */
    public function matrixMultiply(Matrix $B): Matrix
    {
        if ($this->cols !== $B->rows) {
            throw new DimensionException(
                "Matrix dimensions incompatible for multiplication: " .
                "({$this->rows}x{$this->cols}) × ({$B->rows}x{$B->cols})"
            );
        }

        $result = [];
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $B->cols; $j++) {
                $sum = 0;
                for ($k = 0; $k < $this->cols; $k++) {
                    $sum += $this->data[$i][$k] * $B->get($k, $j);
                }
                $result[$i][$j] = $sum;
            }
        }

        return new Matrix($result);
    }

    /**
     * Create identity matrix of size n
     *
     * @param int $n Size of identity matrix
     * @return Matrix
     */
    public static function identity(int $n): Matrix
    {
        $data = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $data[$i][$j] = ($i === $j) ? 1 : 0;
            }
        }
        return new Matrix($data);
    }

    /**
     * Create zero matrix
     *
     * @param int $rows Number of rows
     * @param int $cols Number of columns
     * @return Matrix
     */
    public static function zeros(int $rows, int $cols): Matrix
    {
        $data = array_fill(0, $rows, array_fill(0, $cols, 0));
        return new Matrix($data);
    }

    /**
     * Create matrix filled with ones
     *
     * @param int $rows Number of rows
     * @param int $cols Number of columns
     * @return Matrix
     */
    public static function ones(int $rows, int $cols): Matrix
    {
        $data = array_fill(0, $rows, array_fill(0, $cols, 1));
        return new Matrix($data);
    }

    /**
     * Get number of rows
     *
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * Get number of columns
     *
     * @return int
     */
    public function getCols(): int
    {
        return $this->cols;
    }

    /**
     * Get raw data array
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Replace a row with new data
     * 
     * @param array $data
     * @param int $i
     * @return void
     * @throws DimensionException
     * @throws InvalidArgumentException
     */
    public function setRow(array $data, int $i): void{

        if($this->cols != count($data)){
            throw new DimensionException(
                "Dimensions are incompatible "
            );
        }

        if($i<0 || $i >= $this->rows){
            throw InvalidArgumentException::forInvalidIndex($i, $this->rows - 1, "row");
        }

        $this->data[$i] = $data;
    }

    /**
     * Replace a column with new data
     * 
     * @param array $data
     * @param int $j
     * @return void
     * @throws DimensionException
     * @throws InvalidArgumentException
     */
    public function setColumn(array $data, int $j): void  {

        if($this->rows != count($data)) {
            throw new DimensionException(
                "Dimensions are incompatible "
            );
        }

        if($j<0 || $j >= $this->cols){
            throw InvalidArgumentException::forInvalidIndex($j, $this->cols - 1, "col");
        }

        for($i=0;$i<$this->rows;$i++)
            $this->data[$i][$j] = $data[$i];
    }

    /**
     * Set raw data array
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
        $this->rows = count($data);
        $this->columns = count($data[0]);
    }


    /**
     * Convert to string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        $output = "";
        for ($i = 0; $i < $this->rows; $i++) {
            $output .= "[ ";
            for ($j = 0; $j < $this->cols; $j++) {
                $output .= sprintf("%8.4f ", $this->data[$i][$j]);
            }
            $output .= "]\n";
        }
        return $output;
    }

    /**
     * Validate input data structure
     *
     * @param array $data
     * @throws DimensionException
     */
    private function validateData(array $data): void
    {
        if (empty($data)) {
            throw new DimensionException("Matrix cannot be empty");
        }

        if (!is_array($data[0])) {
            throw new DimensionException("Matrix must be 2-dimensional array");
        }

        $cols = count($data[0]);
        foreach ($data as $row) {
            if (count($row) !== $cols) {
                throw new DimensionException("All rows must have same number of columns");
            }
        }
    }
}