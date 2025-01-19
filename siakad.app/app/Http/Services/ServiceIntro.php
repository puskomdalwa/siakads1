<?php

namespace App\Http\Services;
use App\Intro;
use Illuminate\Support\Facades\Auth;

class ServiceIntro
{
    public static function check()
    {
        $userId = Auth::user()->id;
        $path = request()->path();
        $intro = Intro::where([
            ['user_id', $userId],
            ['path', $path],
        ])->first();

        $statusIntro = 0;
        if ($intro) {
            $statusIntro = $intro->status;
            if (!$statusIntro) {
                $intro->update([
                    'status' => 1,
                ]);
            }
        }

        if ($intro == null) {
            Intro::create([
                'path' => $path,
                'user_id' => $userId,
                'status' => 1,
            ]);
        }

        return $statusIntro;
    }
}
