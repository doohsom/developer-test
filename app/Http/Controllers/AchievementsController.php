<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Helpers\Data;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $find = User::find($user->id);
        if($find){
            $unlockedAchievement = Achievement::where('user_id', $user->id)->pluck('achievement')->get();
            $getCurrentBadge = Achievement::where('user_id', $user->id)->where('type', 'Badge')->latest()->first();
            if(empty($getCurrentBadge)){
                $currentBadge = 'Beginner';
                $badgeValue = 0;
            }
            $currentBadge = $getCurrentBadge->description;
            $badgeValue = $getCurrentBadge->points;
            
            
            $nextAvaliableAchievement = $this->getNextAchievement($user->id);

            $nextBadge = $this->nextBadge($find->id);
            $countAchievement = $this->countAchievement($user->id);

            $remaining = $nextBadge - $countAchievement;
            return response()->json([
                'unlocked_achievements' => $unlockedAchievement,
                'next_available_achievements' => $nextAvaliableAchievement,
                'current_badge' => $currentBadge,
                'next_badge' => $this->nextBadge($badgeValue),
                'remaing_to_unlock_next_badge' => $remaining
            ]);
        }
        
    }

    
private function nextBadge($value)
    {
        $badges = Data::badge()->all();
        $badgeValue = "";
        foreach($badges as $badge){
            if($badge['points'] === $value){
                $badgeValue = $badges['description'];
            }
        }
        return $badgeValue;
    }

    private function countAchievement($user_id)
    {
        $achievementCount = Achievement::where('user_id', $user_id)->where('type', '!=', 'Badge')->count();
        return $achievementCount;
    }

    private function getNextAchievement($user_id)
    {
        $getLesson = Achievement::where(['user_id' => $user_id, 'type' => 'Lesson'])->pluck('points');
        $getComment = Achievement::where(['user_id' => $user_id, 'type' => 'Comment'])->pluck('points');
        $dataLesson = Data::lessons()->pluck('points')->toArray();
        $dataComment = Data::comment()->pluck('points')->toArray();
        
        $diffLesson = current(array_diff($dataLesson, $getLesson));
        $diffComment = current(array_diff($dataComment, $getComment));

        $lessons = Data::lessons()->all();
        $comments = Data::comment()->all();
        $lessonValue = "";
        foreach($lessons as $lesson){
            if($lesson['points'] === $diffLesson){
                $lessonValue = $lesson['description'];
            }
        }
        $commentValue = "";
        foreach($comments as $comment){
            if($comment['points'] === $diffComment){
                $lessonValue = $comment['description'];
            }
        }

        return json_encode([$lessonValue, $commentValue]);
    }
}
