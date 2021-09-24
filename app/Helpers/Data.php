<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class Data
{
    public static function lessons()
    {
        $lessons = collect([
            ['description' => 'First Lesson Watched', 'points' => 1],
            ['description' => '5 Lessons Watched', 'points' => 5],
            ['description' => '10 Lessons Watched', 'points' => 10],
            ['description' => '25 Lessons Watched', 'points' => 25],
            ['description' => '50 Lessons Watched', 'points' => 50]
        ]);

        return $lessons;
    }

    public static function comment()
    {
        $comments = collect([
            ['description' => 'First Comment Written', 'points' => 1],
            ['description' => '3 Comments Written', 'points' => 3],
            ['description' => '5 Comments Written', 'points' => 5],
            ['description' => '10 Comments Written', 'points' => 10],
            ['description' => '20 Comments Written', 'points' => 20]
        ]);

        return $comments;
    }

    public static function badge()
    {
        $badges = collect([
            ['description' => 'Beginner', 'points' => 0],
            ['description' => 'Intermediate', 'points' => 4],
            ['description' => 'Advanced', 'points' => 8],
            ['description' => 'Master', 'points' => 10]
        ]);

        return $badges;
    }
}