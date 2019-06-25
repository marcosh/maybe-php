<?php

declare(strict_types=1);

namespace Marcosh\Maybe;

/**
 * @template A
 */
final class Maybe
{
    /**
     * @var bool
     */
    private $isJust;

    /**
     * @var mixed
     * @psalm-var A
     */
    private $content;

    /**
     * @param bool $isJust
     * @param mixed $content
     * @psalm-param A $content
     */
    private function __construct(
        bool $isJust,
        $content
    ) {
        $this->isJust = $isJust;
        $this->content = $content;
    }

    /**
     * @param mixed $content
     * @psalm-param A $content
     * @return self
     * @psalm-return Maybe<A>
     */
    public static function just($content): self
    {
        return new self(true, $content);
    }

    /**
     * @return self
     * @psalm-return Maybe<A>
     */
    public static function nothing(): self
    {
        return new self(false, null);
    }

    /**
     * @template B
     * @param callable $processJust
     * @psalm-param callable(A): B $processJust
     * @param callable $processNothing
     * @psalm-param callable(): B $processNothing
     * @return mixed
     * @psalm-return B
     */
    public function fold(
        callable $processJust,
        callable $processNothing
    ) {
        if (! $this->isJust) {
            return $processNothing();
        }

        return $processJust($this->content);
    }

    /**
     * @template C
     * @param callable $map
     * @psalm-param callable(A): C $map
     * @return self
     * @psalm-return Maybe<C>
     */
    public function map(callable $map): self
    {
        return $this->fold(
            /**
             * @psalm-param A $content
             * @psalm-return Maybe<C>
             */
            static function ($content) use ($map) {
                return self::just($map($content));
            },
            /**
             * @psalm-return Maybe<C>
             */
            static function () {
                return self::nothing();
            }
        );
    }
}
