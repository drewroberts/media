<?php

use DrewRoberts\Media\Support\YouTube\YouTubeIdParser;

it('parses watch URL with v parameter', function () {
    expect(YouTubeIdParser::parse('https://www.youtube.com/watch?v=moSFlvxnbgk'))
        ->toBe('moSFlvxnbgk');
});

it('parses youtu.be short URL', function () {
    expect(YouTubeIdParser::parse('https://youtu.be/dQw4w9WgXcQ'))
        ->toBe('dQw4w9WgXcQ');
});

it('parses shorts URL', function () {
    expect(YouTubeIdParser::parse('https://www.youtube.com/shorts/abcDEF12345'))
        ->toBe('abcDEF12345');
});

it('parses embed URL', function () {
    expect(YouTubeIdParser::parse('https://www.youtube.com/embed/abcd_ef-1234'))
        ->toBe('abcd_ef-1234');
});

it('parses live URL', function () {
    expect(YouTubeIdParser::parse('https://www.youtube.com/live/xyzABC789'))
        ->toBe('xyzABC789');
});

it('accepts plain IDs directly', function () {
    expect(YouTubeIdParser::parse('iKHTawgyKWQ'))
        ->toBe('iKHTawgyKWQ');
});

it('trims and parses with surrounding whitespace', function () {
    expect(YouTubeIdParser::parse('  https://youtu.be/dQw4w9WgXcQ  '))
        ->toBe('dQw4w9WgXcQ');
});

it('returns null for invalid inputs', function () {
    expect(YouTubeIdParser::parse('https://www.example.com'))
        ->toBeNull();
});
