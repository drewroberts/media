<?php

use DrewRoberts\Media\Support\YouTube\YouTubeIdParser;

describe('YouTube ID Parser', function () {
    describe('URL Format Support', function () {
        test('watch URLs with v parameter', function () {
            expect(YouTubeIdParser::parse('https://www.youtube.com/watch?v=moSFlvxnbgk'))
                ->toBe('moSFlvxnbgk');
        });

        test('youtu.be short URLs', function () {
            expect(YouTubeIdParser::parse('https://youtu.be/dQw4w9WgXcQ'))
                ->toBe('dQw4w9WgXcQ');
        });

        test('YouTube shorts URLs', function () {
            expect(YouTubeIdParser::parse('https://www.youtube.com/shorts/abcDEF12345'))
                ->toBe('abcDEF12345');
        });

        test('embed URLs', function () {
            expect(YouTubeIdParser::parse('https://www.youtube.com/embed/abcd_ef-1234'))
                ->toBe('abcd_ef-1234');
        });

        test('live stream URLs', function () {
            expect(YouTubeIdParser::parse('https://www.youtube.com/live/xyzABC789'))
                ->toBe('xyzABC789');
        });
    });

    describe('Input Handling', function () {
        test('plain video IDs directly', function () {
            expect(YouTubeIdParser::parse('iKHTawgyKWQ'))
                ->toBe('iKHTawgyKWQ');
        });

        test('trimming whitespace around URLs', function () {
            expect(YouTubeIdParser::parse('  https://youtu.be/dQw4w9WgXcQ  '))
                ->toBe('dQw4w9WgXcQ');
        });

        test('invalid URLs return null', function () {
            expect(YouTubeIdParser::parse('https://www.example.com'))
                ->toBeNull();
        });
    });
});
