<?php

namespace DBTiny\QueryBuilder;

use InvalidArgumentException;

class Expression
{
    const OPERATOR_EQ       = '=';
    const OPERATOR_NEQ      = '<>';
    const OPERATOR_LT       = '<';
    const OPERATOR_LTE      = '<=';
    const OPERATOR_GT       = '>';
    const OPERATOR_GTE      = '>=';
    const OPERATOR_IN       = ' IN';
    const OPERATOR_NOT_IN   = ' NOT IN';
    const OPERATOR_LIKE     = ' LIKE ';
    const OPERATOR_NOT_LIKE = ' NOT LIKE ';

    private $parts = [];

    private function compare($a, $operator, $b)
    {
        $this->parts[] = $a . $operator . $b;

        return $this;
    }

    private function joinLastParts($num, $glue)
    {
        //Splice off the last $num elements from the parts stack and glue them together.
        $part = implode(
            $glue,
            array_splice(
                $this->parts,
                -$num,
                count($this->parts),
                []
            )
        );

        $this->parts[] = "({$part})";

        return $this;
    }

    public function andX($expr)
    {
        if ($expr !== $this) {
            $this->parts[] = $expr;
        }

        return $this->joinLastParts(func_num_args() + 1, ' AND ');
    }

    public function orX($expr)
    {
        if ($expr !== $this) {
            $this->parts[] = $expr;
        }

        return $this->joinLastParts(func_num_args() + 1, ' OR ');
    }

    public function lt($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LT, $b);
    }

    public function lte($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LTE, $b);
    }

    public function gt($a, $b)
    {
        return $this->compare($a, self::OPERATOR_GT, $b);
    }

    public function gte($a, $b)
    {
        return $this->compare($a, self::OPERATOR_GTE, $b);
    }

    public function eq($a, $b)
    {
        if (is_array($b)) {
            if (count($b) === 1) {
                $b = current($b);
            } else {
                return $this->in($a, $b);
            }
        }

        return $this->compare($a, self::OPERATOR_EQ, $b);
    }

    public function neq($a, $b)
    {
        if (is_array($b)) {
            if (count($b) === 1) {
                $b = current($b);
            } else {
                return $this->notIn($a, $b);
            }
        }

        return $this->compare($a, self::OPERATOR_NEQ, $b);
    }

    public function like($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LIKE, $b);
    }

    public function notLike($a, $b)
    {
        return $this->compare($a, self::OPERATOR_NOT_LIKE, $b);
    }

    public function isNull($a)
    {
        $this->parts[] = "{$a} IS NULL";

        return $this;
    }

    public function isNotNull($a)
    {
        $this->parts[] = "{$a} IS NOT NULL";

        return $this;
    }

    public function between($a, $b, $c)
    {
        $this->parts[] = "{$a} BETWEEN {$b} AND {$c}";

        return $this;
    }

    public function notBetween($a, $b, $c)
    {
        $this->parts[] = "{$a} NOT BETWEEN {$b} AND {$c}";

        return $this;
    }

    private function implodeInParts($in)
    {
        if (is_array($in)) {
            return implode(', ', $in);
        } elseif ($in instanceof Select) {
            return $in->get();
        } elseif (is_string($in)) {
            return $in;
        }

        throw new InvalidArgumentException('In expects an array, a Select object or a string.');
    }

    public function in($a, $in)
    {
        $in = $this->implodeInParts($in);

        return $this->compare($a, self::OPERATOR_IN, "({$in})");
    }

    public function notIn($a, $in)
    {
        $in = $this->implodeInParts($in);

        return $this->compare($a, self::OPERATOR_NOT_IN, "({$in})");
    }

    public function get()
    {
        return $this->parts[0];
    }

    public function __toString()
    {
        return $this->get();
    }
}
