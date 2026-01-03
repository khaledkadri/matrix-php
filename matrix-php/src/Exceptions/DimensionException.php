<?php

namespace MatrixPHP\Exceptions;

/**
 * Exception thrown when matrix or vector dimensions are incompatible
 * 
 * This exception is thrown when operations are attempted on matrices or vectors
 * with incompatible dimensions, such as:
 * - Adding matrices of different sizes
 * - Multiplying matrices where columns of A ≠ rows of B
 * - Calculating determinant of non-square matrix
 * - Vector operations on vectors of different sizes
 * 
 * @package MatrixPHP\Exceptions
 */
class DimensionException extends MatrixException
{
    /**
     * Create exception for dimension mismatch
     *
     * @param string $message Description of the dimension error
     * @param int $code Error code (default: 0)
     * @param \Exception|null $previous Previous exception for chaining
     */
    public function __construct(string $message = "Matrix dimension mismatch", int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception for incompatible matrix addition
     *
     * @param int $rows1 Rows of first matrix
     * @param int $cols1 Columns of first matrix
     * @param int $rows2 Rows of second matrix
     * @param int $cols2 Columns of second matrix
     * @return self
     */
    public static function forAddition(int $rows1, int $cols1, int $rows2, int $cols2): self
    {
        return new self(
            "Cannot add matrices: ({$rows1}×{$cols1}) and ({$rows2}×{$cols2}) must have same dimensions"
        );
    }

    /**
     * Create exception for incompatible matrix multiplication
     *
     * @param int $rows1 Rows of first matrix
     * @param int $cols1 Columns of first matrix
     * @param int $rows2 Rows of second matrix
     * @param int $cols2 Columns of second matrix
     * @return self
     */
    public static function forMultiplication(int $rows1, int $cols1, int $rows2, int $cols2): self
    {
        return new self(
            "Cannot multiply matrices: ({$rows1}×{$cols1}) × ({$rows2}×{$cols2}) - " .
            "columns of first matrix ({$cols1}) must equal rows of second matrix ({$rows2})"
        );
    }

    /**
     * Create exception for non-square matrix operations
     *
     * @param int $rows Number of rows
     * @param int $cols Number of columns
     * @param string $operation Operation that requires square matrix
     * @return self
     */
    public static function forSquareRequired(int $rows, int $cols, string $operation = "operation"): self
    {
        return new self(
            "{$operation} requires square matrix, but matrix is ({$rows}×{$cols})"
        );
    }

    /**
     * Create exception for incompatible vector operations
     *
     * @param int $size1 Size of first vector
     * @param int $size2 Size of second vector
     * @param string $operation Operation being attempted
     * @return self
     */
    public static function forVectors(int $size1, int $size2, string $operation = "operation"): self
    {
        return new self(
            "Cannot perform {$operation}: vectors must have same size, but have {$size1} and {$size2}"
        );
    }

    /**
     * Create exception for empty matrix/vector
     *
     * @param string $type Type of object (matrix or vector)
     * @return self
     */
    public static function forEmpty(string $type = "matrix"): self
    {
        return new self(ucfirst($type) . " cannot be empty");
    }

    /**
     * Create exception for invalid matrix structure
     *
     * @param string $reason Reason for invalid structure
     * @return self
     */
    public static function forInvalidStructure(string $reason): self
    {
        return new self("Invalid matrix structure: {$reason}");
    }
}
