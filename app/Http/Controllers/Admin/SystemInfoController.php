<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemInfoController extends Controller
{
    public function index()
    {
        // OS Info
        $os = php_uname('s') . ' ' . php_uname('r');
        
        // Software Versions
        $phpVersion = phpversion();
        $laravelVersion = app()->version();
        
        // MySQL Version
        $mysqlVersion = 'Unknown';
        try {
            $pdo = DB::connection()->getPdo();
            $mysqlVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
        } catch (\Exception $e) {
            // Ignore
        }
        
        // Server Software
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown/CLI';
        
        // Disk Space
        $freeDisk = disk_free_space(base_path());
        $totalDisk = disk_total_space(base_path());
        $usedDisk = $totalDisk - $freeDisk;
        $diskUsagePercent = $totalDisk > 0 ? round(($usedDisk / $totalDisk) * 100, 1) : 0;
        
        // Memory Usage
        $memoryUsage = memory_get_usage(true);
        $peakMemoryUsage = memory_get_peak_usage(true);
        
        // CPU Load (Linux/Mac only)
        $cpuLoad = null;
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load !== false) {
                $cpuLoad = $load[0];
            }
        }
        
        // Helper to format bytes
        $formatBytes = function ($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        };

        return view('admin.system-info.index', compact(
            'os', 'phpVersion', 'laravelVersion', 'mysqlVersion', 'serverSoftware',
            'freeDisk', 'totalDisk', 'usedDisk', 'diskUsagePercent',
            'memoryUsage', 'peakMemoryUsage', 'cpuLoad', 'formatBytes'
        ));
    }
}
