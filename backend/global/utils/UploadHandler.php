<?php

class UploadHandler
{
    private static $baseDir = __DIR__ . '/../../../uploads/';
    private static $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private static $allowedVideoExts = ['mp4', 'webm', 'mov'];
    private static $maxSize = 10 * 1024 * 1024; // 10MB

    public static function init()
    {
        if (!file_exists(self::$baseDir)) {
            mkdir(self::$baseDir, 0777, true);
        }
    }

    /**
     * Upload a file to a specific subdirectory.
     * Returns the relative path to the file or throws an Exception.
     */
    public static function uploadFile($file, $subDir = 'general')
    {
        self::init();
        $targetDir = self::$baseDir . $subDir . '/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $tmpName = $file['tmp_name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate size
        if ($fileSize > self::$maxSize) {
            throw new Exception("File too large. Max 10MB.");
        }

        // Validate extension
        if (!in_array($ext, array_merge(self::$allowedImageExts, self::$allowedVideoExts))) {
            throw new Exception("Invalid file type (.$ext).");
        }

        // Generate unique name
        $newFileName = uniqid($subDir . '_', true) . '.' . $ext;
        $targetPath = $targetDir . $newFileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            return 'uploads/' . $subDir . '/' . $newFileName;
        } else {
            throw new Exception("Failed to save file.");
        }
    }

    /**
     * Upload multiple files.
     */
    public static function uploadMultiple($files, $subDir = 'general')
    {
        $paths = [];
        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $files['name'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'size' => $files['size'][$key],
                    'error' => $files['error'][$key]
                ];
                $paths[] = self::uploadFile($file, $subDir);
            }
        }
        return $paths;
    }
}
