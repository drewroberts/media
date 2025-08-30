<?php

use DrewRoberts\Media\Support\YouTube\Duration;

it('converts full HMS duration to seconds', function () {
    expect(Duration::iso8601ToSeconds('PT1H2M3S'))->toBe(3723);
});

it('converts minutes and seconds only', function () {
    expect(Duration::iso8601ToSeconds('PT5M10S'))->toBe(310);
});

it('converts hours only', function () {
    expect(Duration::iso8601ToSeconds('PT2H'))->toBe(7200);
});

it('returns null for invalid format', function () {
    expect(Duration::iso8601ToSeconds('P1DT2H'))->toBeNull();
    expect(Duration::iso8601ToSeconds(''))->toBeNull();
    expect(Duration::iso8601ToSeconds(null))->toBeNull();
});
