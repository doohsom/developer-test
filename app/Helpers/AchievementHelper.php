<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Achievement;
use App\Helpers\Data;
use Carbon\Carbon;

class AchievementHelper
{
   public function checkLessonWatched(User $user)
   {
       
    $data = Data::lessons()->pluck('points')->toArray();    
    $countLessonWatched = $user->watched()->count;
    if (! in_array($countLessonWatched, $data)) {
        return;
    }
    $description = Data::lessons()->where('points', $countLessonWatched)->first();
    $this->unlock($description->description, 'Lesson', $user->id, $description->points);
    $this->checkBadge($user->id);
    return true;
   }

   private function checkBadge(User $user)
   {
    $badges = Achievement::where('user_id', $user->id)->count();       
    $data = Data::badge()->pluck('points')->toArray();    
    if (! in_array($badges, $data)) {
        return;
    }
    $description = Data::badge()->where('points', $badges)->first();
    $this->unlock($description->description, 'Badge', $user->id, $description->points);
   }

   

   private function unlock($description, $type, $user_id, $points)
   {
    try {

        $saved = Achievement::create([
            'achievement' => $description,
            'type' => $type,
            'user_id' => $user_id,
            'points' => $points,
            'time_unlocked' => Carbon::now()
        ]);

        if($saved){
            return true;
        } else {
            return false;
        }
    } catch (\Exception $e) {
        return false;
    }
        
   }
}