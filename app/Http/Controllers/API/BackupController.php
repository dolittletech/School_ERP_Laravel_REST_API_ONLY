<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:backup.view');
    // }

    public function backup(Request $request)
    {
        // if (!auth()->check() || !auth()->user()->can('backup.view')) {
        //     return response()->json(['error' => 'Unauthorized'], 403);
        // }

        if ($request->isMethod('post')) {
            // Handle Upload
            if ($request->input('backup') === 'upload') {
                $validator = Validator::make($request->all(), [
                    'file' => 'required|file|mimes:sql|max:10240', // Max 10MB
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $file = $request->file('file');
                $fileName = 'db-' . now()->format('Y-m-d_H-i-s') . '.sql';
                $file->storeAs('temp_uploaded', $fileName, 'backups');

                $filePath = storage_path('app/backups/temp_uploaded/' . $fileName);
                $sqlContent = file_get_contents($filePath);
                $queries = explode(';', $sqlContent);

                DB::beginTransaction();
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                    foreach ($queries as $query) {
                        $trimmedQuery = trim($query);
                        if (!empty($trimmedQuery)) {
                            DB::statement($trimmedQuery);
                        }
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                    DB::commit();
                    return redirect()->route('admin.backup')
                                     ->with('success', 'Backup restored successfully!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to restore backup: ' . $e->getMessage()], 500);
                }
            }

            // Handle Create Backup
            if ($request->input('backup') === 'backup') {
                $filename = 'db_ver_' . config('app.version', '1.0') . '_' . now()->format('Y-m-d_H-i-s') . '.sql';
                $backupPath = storage_path('app/backups/database_backup/' . $filename);

                // Use Laravel’s artisan command for database backup (requires spatie/laravel-backup or similar)
                // Alternatively, use mysqldump manually
                $command = sprintf(
                    'mysqldump --user=%s --password=%s --host=%s %s > %s',
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.database'),
                    $backupPath
                );

                exec($command, $output, $returnVar);

                if ($returnVar === 0) {
                    return redirect()->route('admin.backup')
                                     ->with('success', 'Backup created successfully!');
                }
                return response()->json(['error' => 'Failed to create backup'], 500);
            }

            // Handle Restore
            if ($request->input('backup') === 'restore') {
                $fileName = $request->input('filename');
                $filePath = storage_path('app/backups/database_backup/' . $fileName);

                if (!file_exists($filePath)) {
                    return response()->json(['error' => 'Backup file not found'], 404);
                }

                $sqlContent = file_get_contents($filePath);
                $queries = explode(';', $sqlContent);

                DB::beginTransaction();
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                    foreach ($queries as $query) {
                        $trimmedQuery = trim($query);
                        if (!empty($trimmedQuery)) {
                            DB::statement($trimmedQuery);
                        }
                    }
                    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
                    DB::commit();
                    return redirect()->route('admin.backup')
                                     ->with('success', 'Backup restored successfully!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Failed to restore backup: ' . $e->getMessage()], 500);
                }
            }
        }

        // List Backup Files
        $backupDir = storage_path('app/backups/database_backup');
        $files = array_diff(scandir($backupDir), ['.', '..']);
        $settingList = (new Backup())->get();

        return response()->json([
            'dbfileList' => $files,
            'settinglist' => $settingList,
        ]);
    }

    public function download($filename)
    {
        $filePath = storage_path('app/backups/database_backup/' . $filename);
        if (file_exists($filePath)) {
            return response()->download($filePath, $filename);
        }
        return response()->json(['error' => 'File not found'], 404);
    }

    public function delete($filename)
    {
        if (!auth()->check() || !auth()->user()->can('backup.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $filePath = storage_path('app/backups/database_backup/' . $filename);
        if (file_exists($filePath) && unlink($filePath)) {
            return redirect()->route('admin.backup')
                             ->with('success', 'Backup deleted successfully!');
        }
        return response()->json(['error' => 'Failed to delete backup'], 500);
    }
}