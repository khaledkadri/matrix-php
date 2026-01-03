<?php

require_once 'src/Exceptions/MatrixException.php';
require_once 'src/Exceptions/DimensionException.php';
require_once 'src/Exceptions/SingularMatrixException.php';
require_once 'src/Exceptions/InvalidArgumentException.php';
require_once 'src/Matrix.php';

use MatrixPHP\Matrix;

// Create a matrix
$A = new Matrix([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
]);

// Basic operations
$B = new Matrix([[1, 0, 0], [0, 1, 0], [0, 0, 1]]);
$C = $A->add($B);           // Matrix addition
$D = $A->multiply(2);       // Scalar multiplication
$E = $A->transpose();       // Transpose

// Advanced operations
$det = $A->determinant();   // Calculate determinant
echo "Det(A) = ".$det."<br>";

$inv = $B->inverse();       // Matrix inverse (if non-singular)
echo "Inverse : ".$A;

// Matrix multiplication
$result = $A->matrixMultiply($B);

echo $A;


// Replace a row
$A->setRow([10, 11, 12], 1);
echo $A;

// Replace a column
$matrix->setColumn([20, 21, 22], 0);
echo $A;

// Update all data
$matrix->setData([[1,1], [2,2]]);
echo $A;
