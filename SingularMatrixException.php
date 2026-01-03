<?php

namespace MatrixPHP\Exceptions;

/**
 * Exception thrown when attempting operations on singular (non-invertible) matrices
 * 
 * A matrix is singular when its determinant equals zero, meaning:
 * - The matrix is not invertible
 * - The rows/columns are linearly dependent
 * - The system has no unique solution or infinitely many solutions
 * 
 * This exception is thrown when attempting operations that require non-singular matrices,
 * such as matrix inversion or solving linear systems with unique solutions.
 * 
 * @package MatrixPHP\Exceptions
 */
class SingularMatrixException extends MatrixException
{
    /**
     * Create exception for singular matrix
     *
     * @param string $message Description of the error
     * @param int $code Error code (default: 0)
     * @param \Exception|null $previous Previous exception for chaining
     */
    public function __construct(string $message = "Matrix is singular (determinant = 0)", int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception with determinant value
     *
     * @param float $determinant The calculated determinant value
     * @param float $tolerance Tolerance used to determine singularity
     * @return self
     */
    public static function withDeterminant(float $determinant, float $tolerance = 1e-10): self
    {
        return new self(
            "Matrix is singular: determinant = " . number_format($determinant, 15) . 
            " (below tolerance of {$tolerance})"
        );
    }

    /**
     * Create exception for matrix inversion failure
     *
     * @param float|null $determinant The determinant value if available
     * @return self
     */
    public static function forInversion(?float $determinant = null): self
    {
        if ($determinant !== null) {
            return new self(
                "Cannot compute matrix inverse: matrix is singular (determinant = " . 
                number_format($determinant, 15) . ")"
            );
        }
        return new self("Cannot compute matrix inverse: matrix is singular");
    }

    /**
     * Create exception for linear system solving failure
     *
     * @param string $reason Additional context about why the system cannot be solved
     * @return self
     */
    public static function forLinearSystem(string $reason = ""): self
    {
        $message = "Cannot solve linear system: matrix is singular";
        if (!empty($reason)) {
            $message .= " - {$reason}";
        }
        return new self($message);
    }

    /**
     * Create exception for division operation
     *
     * @return self
     */
    public static function forDivision(): self
    {
        return new self(
            "Cannot perform matrix division: divisor matrix is singular and cannot be inverted"
        );
    }

    /**
     * Create exception when matrix is numerically singular (near-zero determinant)
     *
     * @param float $determinant The near-zero determinant value
     * @param float $threshold Threshold below which matrix is considered numerically singular
     * @return self
     */
    public static function numericallyNearSingular(float $determinant, float $threshold = 1e-10): self
    {
        return new self(
            "Matrix is numerically near-singular: determinant = " . 
            number_format($determinant, 15) . 
            " (below numerical stability threshold of {$threshold}). " .
            "Results may be unreliable due to floating-point precision limits."
        );
    }
}
