<?php
// Routes

$o_directory = new RecursiveDirectoryIterator(__DIR__ . "/Routes/", FilesystemIterator::KEY_AS_PATHNAME | 
                                                                    FilesystemIterator::CURRENT_AS_FILEINFO |
                                                                    FilesystemIterator::SKIP_DOTS);

$o_iterator = new RecursiveIteratorIterator($o_directory);

foreach($o_iterator as $str_filename => $o_file)
{
    if ($o_file->isFile())
        require $str_filename;
}