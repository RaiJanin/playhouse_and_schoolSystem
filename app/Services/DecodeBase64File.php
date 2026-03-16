<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class DecodeBase64File
{
    public static function makeFile(string $rawPhoto, string $directory, string $filename)
    {
        $photoPath = null;
        $rootPath = 'uploads/';

        if (!preg_match('/^data:image\/(\w+);base64,/', $rawPhoto, $type)) 
        {
            return null;
        }
        
        $data = substr($rawPhoto, strpos($rawPhoto, ',') + 1);
        $data = base64_decode($data);

        $extension = $type[1]; // e.g., jpg, png
        if(!in_array($extension, ['jpg','jpeg','png','gif','webp']))
        {
            return null;
        }

        $filename = $filename . time() . '.' . $extension;
        $photoPath = $rootPath . $directory . '/' . $filename;

        Storage::disk('public')->put($photoPath, $data);

        return 'storage/' . $photoPath;
    }

    public static function deleteFile($path)
    {
        $file = urldecode($path);

        if (Storage::disk('public')->delete($path)) {
            return true;
        }

        return false;
    }
}