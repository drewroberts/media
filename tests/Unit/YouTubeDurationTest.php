<?php

use DrewRoberts\Media\Support\YouTube\Duration;

describe('YouTube Duration Parser', function () {
    describe('ISO 8601 Conversion', function () {
        test('full hours, minutes, and seconds format', function () {
            expect(Duration::iso8601ToSeconds('PT1H2M3S'))->toBe(3723);
        });

        test('minutes and seconds only', function () {
            expect(Duration::iso8601ToSeconds('PT5M10S'))->toBe(310);
        });

        test('hours only format', function () {
            expect(Duration::iso8601ToSeconds('PT2H'))->toBe(7200);
        });
    });

    describe('Invalid Input Handling', function () {
        test('unsupported date components return null', function () {
            expect(Duration::iso8601ToSeconds('P1DT2H'))->toBeNull();
        });

        test('empty strings return null', function () {
            expect(Duration::iso8601ToSeconds(''))->toBeNull();
        });

        test('null input returns null', function () {
            expect(Duration::iso8601ToSeconds(null))->toBeNull();
        });
    });
});
