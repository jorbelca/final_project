<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;

class Notify
{
    public static function notify(String $route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route($route)->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route($route)->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
