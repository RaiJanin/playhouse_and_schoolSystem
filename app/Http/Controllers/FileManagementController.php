<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\DecodeBase64File;

class FileManagementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page');
        
        $allFiles = Storage::disk('public')->allFiles('uploads');
        
        $filesWithMeta = [];
        foreach ($allFiles as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            $filesWithMeta[] = [
                'path' => $file,
                'lastModified' => $lastModified
            ];
        }
        
        usort($filesWithMeta, function($a, $b) {
            return ($b['lastModified'] ?? 0) - ($a['lastModified'] ?? 0);
        });
        
        $currentPage = $request->input('page', 1);
        
        $paginatedFiles = new LengthAwarePaginator(
            $filesWithMeta,
            count($filesWithMeta),
            $perPage,
            $currentPage,
            ['path' => route('files.index')]
        );
        
        $start = ($currentPage - 1) * $perPage;
        $pageFiles = array_slice($filesWithMeta, $start, $perPage);
        
        $paginatedGrouped = [];
        foreach ($pageFiles as $file) {
            if ($file['lastModified']) {
                $date = date('Y-m-d', $file['lastModified']);
            } else {
                $date = 'Unknown';
            }
            if (!isset($paginatedGrouped[$date])) {
                $paginatedGrouped[$date] = [];
            }
            $paginatedGrouped[$date][] = $file['path'];
        }
        
        krsort($paginatedGrouped);
        
        return view('pages.file-manager', [
            'files' => $paginatedGrouped,
            'paginatedFiles' => $paginatedFiles,
            'perPage' => $perPage,
            'totalFiles' => count($allFiles)
        ]);
    }

    public function delete($file)
    {
        if(!DecodeBase64File::deleteFile($file))
        {
            return back()->with('error','File not found');
        }

        return back()->with('success','File deleted');
    }
}
