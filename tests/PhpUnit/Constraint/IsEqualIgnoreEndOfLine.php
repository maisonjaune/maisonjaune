<?php

namespace App\Tests\PhpUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory as ComparatorFactory;

class IsEqualIgnoreEndOfLine extends Constraint
{
    public function __construct(
        private string $value,
        private float  $delta = 0.0,
        private bool   $canonicalize = false,
        private bool   $ignoreCase = false
    )
    {
    }

    public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        $value = implode(PHP_EOL, preg_split('/\R/', $this->value));
        $other = implode(PHP_EOL, preg_split('/\R/', $other));

        if ($value === $other) {
            return true;
        }

        $comparatorFactory = ComparatorFactory::getInstance();

        try {
            $comparator = $comparatorFactory->getComparatorFor($value, $other);

            $comparator->assertEquals(
                $value,
                $other,
                $this->delta,
                $this->canonicalize,
                $this->ignoreCase
            );
        } catch (ComparisonFailure $f) {
            if ($returnResult) {
                return false;
            }

            throw new ExpectationFailedException(trim($description . PHP_EOL . $f->getMessage()), $f);
        }

        return true;
    }

    public function toString(): string
    {
        if (is_string($this->value)) {
            if (str_contains($this->value, PHP_EOL)) {
                return 'is equal to <text>';
            }

            return sprintf("is equal to '%s'", $this->value);
        }

        $delta = $this->delta != 0
            ? sprintf(' with delta <%F>', $this->delta)
            : '';

        return sprintf('is equal to %s%s', $this->exporter()->export($this->value), $delta);
    }
}