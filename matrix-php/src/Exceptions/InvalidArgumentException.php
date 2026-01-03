<?php

namespace MatrixPHP\Exceptions;

/**
 * Exception thrown when invalid arguments are provided to matrix/vector operations
 * 
 * This exception is used for validation errors such as:
 * - Invalid data types
 * - Out of range indices
 * - Invalid parameters (negative sizes, invalid norms, etc.)
 * - Malformed input data
 * 
 * @package MatrixPHP\Exceptions
 */
class InvalidArgumentException extends MatrixException
{
    /**
     * Create exception for invalid argument
     *
     * @param string $message Description of the invalid argument
     * @param int $code Error code (default: 0)
     * @param \Exception|null $previous Previous exception for chaining
     */
    public function __construct(string $message = "Invalid argument provided", int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception for invalid index access
     *
     * @param int $index The invalid index
     * @param int $max Maximum valid index
     * @param string $type Type of index (row, column, element)
     * @return self
     */
    public static function forInvalidIndex(int $index, int $max, string $type = "index"): self
    {
        return new self(
            "Invalid {$type} index: {$index}. Must be between 0 and {$max}"
        );
    }

    /**
     * Create exception for invalid size parameter
     *
     * @param int $size The invalid size
     * @param string $parameter Parameter name (rows, columns, size, etc.)
     * @return self
     */
    public static function forInvalidSize(int $size, string $parameter = "size"): self
    {
        return new self(
            "Invalid {$parameter}: {$size}. Must be a positive integer"
        );
    }

    /**
     * Create exception for invalid data type
     *
     * @param string $expected Expected data type
     * @param string $actual Actual data type received
     * @param string $parameter Parameter name
     * @return self
     */
    public static function forInvalidType(string $expected, string $actual, string $parameter = "parameter"): self
    {
        return new self(
            "Invalid type for {$parameter}: expected {$expected}, got {$actual}"
        );
    }

    /**
     * Create exception for non-numeric values
     *
     * @param mixed $value The non-numeric value
     * @param int|null $row Row index if applicable
     * @param int|null $col Column index if applicable
     * @return self
     */
    public static function forNonNumericValue($value, ?int $row = null, ?int $col = null): self
    {
        $location = "";
        if ($row !== null && $col !== null) {
            $location = " at position ({$row}, {$col})";
        }
        
        $type = gettype($value);
        return new self(
            "Non-numeric value{$location}: expected number, got {$type}"
        );
    }

    /**
     * Create exception for invalid norm type
     *
     * @param string $normType The invalid norm type
     * @param array $validTypes List of valid norm types
     * @return self
     */
    public static function forInvalidNorm(string $normType, array $validTypes = []): self
    {
        $message = "Invalid norm type: '{$normType}'";
        if (!empty($validTypes)) {
            $message .= ". Valid types: " . implode(", ", $validTypes);
        }
        return new self($message);
    }

    /**
     * Create exception for invalid tolerance value
     *
     * @param float $tolerance The invalid tolerance
     * @return self
     */
    public static function forInvalidTolerance(float $tolerance): self
    {
        return new self(
            "Invalid tolerance: {$tolerance}. Must be a positive number"
        );
    }

    /**
     * Create exception for invalid range
     *
     * @param mixed $min Minimum value
     * @param mixed $max Maximum value
     * @param string $parameter Parameter name
     * @return self
     */
    public static function forInvalidRange($min, $max, string $parameter = "value"): self
    {
        return new self(
            "Invalid {$parameter}: must be between {$min} and {$max}"
        );
    }

    /**
     * Create exception for malformed input data
     *
     * @param string $reason Reason why data is malformed
     * @return self
     */
    public static function forMalformedData(string $reason): self
    {
        return new self("Malformed input data: {$reason}");
    }

    /**
     * Create exception for zero division
     *
     * @return self
     */
    public static function forZeroDivision(): self
    {
        return new self("Division by zero is not allowed");
    }

    /**
     * Create exception for zero vector normalization
     *
     * @return self
     */
    public static function forZeroVectorNormalization(): self
    {
        return new self("Cannot normalize zero vector (magnitude = 0)");
    }

    /**
     * Create exception for inconsistent row lengths
     *
     * @param int $expectedLength Expected row length
     * @param int $actualLength Actual row length found
     * @param int $rowIndex Index of problematic row
     * @return self
     */
    public static function forInconsistentRowLength(int $expectedLength, int $actualLength, int $rowIndex): self
    {
        return new self(
            "Inconsistent row length at row {$rowIndex}: expected {$expectedLength} columns, got {$actualLength}"
        );
    }
}
