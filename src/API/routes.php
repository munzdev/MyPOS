<?php
// Routes

$directory = new RecursiveDirectoryIterator(
    __DIR__ . "/Routes/",
    FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
);

$iterator = new RecursiveIteratorIterator($directory);

foreach ($iterator as $filename => $file) {
    if ($file->isFile()) {
        include $filename;
    }
}
