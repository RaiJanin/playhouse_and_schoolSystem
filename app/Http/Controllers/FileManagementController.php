<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\DecodeBase64File;

class FileManagementController extends Controller
{
    public function index()
    {
        $files = Storage::disk('public')->allFiles('uploads');

        return view('file-manager.index', compact('files'));
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
