<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Jangan track admin & asset
        if (!$request->is('admin*') && !$request->is('*.css') && !$request->is('*.js')) {

            $ipAddress = $request->ip();

            // 💡 PERBAIKAN UTAMA: 
            // Cek langsung ke database apakah IP ini SUDAH PERNAH tercatat HARI INI
            $alreadyVisitedToday = Visitor::where('ip_address', $ipAddress)
                ->whereDate('created_at', today())
                ->exists();

            // Jika hari ini IP tersebut belum pernah dicatat, baru kita masukkan ke database
            if (!$alreadyVisitedToday) {

                $agent = $request->userAgent() ?? '';

                // Detect browser
                $browser = 'Unknown';
                if (str_contains($agent, 'Edg'))        $browser = 'Edge';
                elseif (str_contains($agent, 'Chrome')) $browser = 'Chrome';
                elseif (str_contains($agent, 'Firefox'))$browser = 'Firefox';
                elseif (str_contains($agent, 'Safari')) $browser = 'Safari';

                // Detect OS
                $os = 'Unknown';
                if (str_contains($agent, 'Windows'))     $os = 'Windows';
                elseif (str_contains($agent, 'Android')) $os = 'Android';
                elseif (str_contains($agent, 'iPhone'))  $os = 'iOS';
                elseif (str_contains($agent, 'Mac'))     $os = 'MacOS';
                elseif (str_contains($agent, 'Linux'))   $os = 'Linux';

                Visitor::create([
                    'ip_address' => $ipAddress,
                    'browser'    => $browser,
                    'os'         => $os,
                    'page'       => $request->path(), // Menyimpan halaman pertama yang dia datangi hari ini
                ]);
            }
        }

        return $next($request);
    }
}