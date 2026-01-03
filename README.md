# matrix-php
A lightweight and efficient PHP library for matrix and vector operations with a clean object-oriented interface.

MatrixPHP provides a clean, object-oriented interface for matrix and vector 
operations in PHP. With support for basic operations (addition, subtraction, 
multiplication) and advanced features (determinant, inverse, transpose, cofactor), 
it offers comprehensive exception handling and full type hints for modern PHP 
development. Perfect for scientific computing, data analysis, and educational purposes.

## Documentation
### Creating Matrices

```php
require 'vendor/autoload.php';

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
$inv = $B->inverse();       // Matrix inverse (if non-singular)

// Matrix multiplication
$result = $A->matrixMultiply($B);

echo $A;  // Pretty print matrix
```


### Exception Handling
```php
use MatrixPHP\Exceptions\DimensionException;
use MatrixPHP\Exceptions\SingularMatrixException;
use MatrixPHP\Exceptions\InvalidArgumentException;

try {
    $A = new Matrix([[1, 2], [3, 4]]);
    $B = new Matrix([[1], [2], [3]]);
    $C = $A->add($B);  // Dimension mismatch
} catch (DimensionException $e) {
    echo "Error: " . $e->getMessage();
    // "Matrix dimensions must match for addition"
}

try {
    $singular = new Matrix([[1, 2], [2, 4]]);
    $inv = $singular->inverse();  // Matrix is singular
} catch (SingularMatrixException $e) {
    echo "Error: " . $e->getMessage();
    // "Matrix is singular (determinant = 0)"
}
```
