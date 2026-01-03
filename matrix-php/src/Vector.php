<?php

namespace MatrixPHP;

use MatrixPHP\Exceptions\DimensionException;

class Vector
{
    private array $data;
    private int $size;

    /**
     * Create a new Vector instance
     *
     * @param array $data 1D array representing the vector
     */
    public function __construct(array $data)
    {
        $this->data = array_values($data);
        $this->size = count($this->data);
    }

    /**
     * Calculate dot product with another vector
     *
     * @param Vector $v
     * @return float
     * @throws DimensionException
     */
    public function dot(Vector $v): float
    {
        if ($this->size !== $v->size) {
            throw new DimensionException("Vectors must have same size for dot product");
        }

        $sum = 0;
        for ($i = 0; $i < $this->size; $i++) {
            $sum += $this->data[$i] * $v->get($i);
        }

        return $sum;
    }

    /**
     * Add two vectors
     *
     * @param Vector $v
     * @return Vector
     * @throws DimensionException
     */
    public function add(Vector $v): Vector
    {
        if ($this->size !== $v->size) {
            throw new DimensionException("Vectors must have same size for addition");
        }

        $result = [];
        for ($i = 0; $i < $this->size; $i++) {
            $result[$i] = $this->data[$i] + $v->get($i);
        }

        return new Vector($result);
    }

    /**
     * Subtract two vectors
     *
     * @param Vector $v
     * @return Vector
     * @throws DimensionException
     */
    public function subtract(Vector $v): Vector
    {
        if ($this->size !== $v->size) {
            throw new DimensionException("Vectors must have same size for subtraction");
        }

        $result = [];
        for ($i = 0; $i < $this->size; $i++) {
            $result[$i] = $this->data[$i] - $v->get($i);
        }

        return new Vector($result);
    }

    /**
     * Multiply vector by scalar
     *
     * @param float $scalar
     * @return Vector
     */
    public function multiply(float $scalar): Vector
    {
        $result = array_map(fn($x) => $x * $scalar, $this->data);
        return new Vector($result);
    }

    /**
     * Calculate vector magnitude (L2 norm)
     *
     * @return float
     */
    public function magnitude(): float
    {
        return sqrt($this->dot($this));
    }

    /**
     * Normalize vector
     *
     * @return Vector
     */
    public function normalize(): Vector
    {
        $mag = $this->magnitude();
        if ($mag < 1e-10) {
            throw new \RuntimeException("Cannot normalize zero vector");
        }
        return $this->multiply(1 / $mag);
    }

    /**
     * Get element at index i
     *
     * @param int $i Index
     * @return float
     */
    public function get(int $i): float
    {
        return $this->data[$i];
    }

    /**
     * Get vector size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
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
     * Convert to string
     *
     * @return string
     */
    public function __toString(): string
    {
        return "[ " . implode(", ", array_map(fn($x) => sprintf("%.4f", $x), $this->data)) . " ]";
    }
}