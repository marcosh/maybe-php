<?php

declare(strict_types=1);

namespace Marcosh\MaybeSpec;

use Marcosh\Maybe\Maybe;

describe('Maybe', function () {
    it('uses nothing case when nothing', function () {
        $maybe = Maybe::nothing();

        $folded = $maybe->fold(
            function ($content) {
                return $content;
            },
            function () {
                return 42;
            }
        );

        expect($folded)->toEqual(42);
    });

    it('uses just case when just', function () {
        $maybe = Maybe::just(42);

        $folded = $maybe->fold(
            function ($content) {
                return $content * 2;
            },
            function () {
                return 0;
            }
        );

        expect($folded)->toEqual(84);
    });

    it('maps nothing to nothing', function () {
        $maybe = Maybe::nothing();

        $mapped = $maybe->map(function ($x) {return $x * 2;});

        expect($mapped)->toEqual(Maybe::nothing());
    });

    it('maps uses callable to map just', function () {
        $maybe = Maybe::just(42);

        $mapped = $maybe->map(function ($x) {return $x * 2;});

        expect($mapped)->toEqual(Maybe::just(84));
    });
});