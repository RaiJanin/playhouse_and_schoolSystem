<?php

namespace App\Services;

class DecodeBase64File
{
    public static function makeFile(string $rawPhoto, string $directory, string $filename)
    {
        $photoPath = null;
        if (preg_match('/^data:image\/(\w+);base64,/', $rawPhoto, $type)) {
            $data = substr($rawPhoto, strpos($rawPhoto, ',') + 1);
            $data = base64_decode($data);

            $extension = $type[1]; // e.g., jpg, png
            $filename = $filename . time() . '.' . $extension;

            $directory = rtrim($directory, '/') . '/';
            $fullDirPath = public_path($directory);

            if (!is_dir($fullDirPath)) 
            {
                mkdir($fullDirPath, 0755, true); 
            }

            file_put_contents($fullDirPath . $filename, $data);
            $photoPath = $directory . $filename;
        }

        return $photoPath;
    }
}