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

    public function stats()
    {
        // Disk Space
        $freeDisk = disk_free_space(base_path());
        $totalDisk = disk_total_space(base_path());
        $usedDisk = $totalDisk - $freeDisk;
        $diskUsagePercent = $totalDisk > 0 ? round(($usedDisk / $totalDisk) * 100, 1) : 0;
        
        // Memory Usage
        $memoryUsage = memory_get_usage(true);
        $memLimitStr = ini_get('memory_limit');
        $memLimitBytes = -1;
        if (preg_match('/^(\d+)(.)$/i', trim($memLimitStr), $matches)) {
            $val = (int)$matches[1];
            $unit = strtoupper($matches[2]);
            if ($unit == 'M') $memLimitBytes = $val * 1024 * 1024;
            elseif ($unit == 'G') $memLimitBytes = $val * 1024 * 1024 * 1024;
            elseif ($unit == 'K') $memLimitBytes = $val * 1024;
        }
        $memPercent = $memLimitBytes > 0 ? min(round(($memoryUsage / $memLimitBytes) * 100, 1), 100) : 100;
        
        // CPU Load (Linux/Mac only)
        $cpuLoad = null;
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load !== false) {
                $cpuLoad = $load[0];
            }
        }
        $cpuSupported = $cpuLoad !== null;
        $cpuPercentVal = $cpuSupported ? min(round((float)$cpuLoad * 100, 1), 100) : 0;
        
        // Helper to format bytes
        $formatBytes = function ($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        };

        return response()->json([
            'disk' => [
                'percent' => $diskUsagePercent,
                'used' => $formatBytes($usedDisk),
                'total' => $formatBytes($totalDisk),
                'free' => $formatBytes($freeDisk)
            ],
            'mem' => [
                'percent' => $memPercent,
                'used' => $formatBytes($memoryUsage),
                'total' => $memLimitBytes > 0 ? $formatBytes($memLimitBytes) : "Tidak Terbatas",
                'free' => $memLimitBytes > 0 ? $formatBytes(max($memLimitBytes - $memoryUsage, 0)) : "Tidak Terbatas",
                'hasLimit' => $memLimitBytes > 0
            ],
            'cpu' => [
                'percent' => $cpuPercentVal,
                'supported' => $cpuSupported
            ]
        ]);
    }
}
