<?php
declare(strict_types = 1);
namespace App\Helpers;

class Config
{
    /**
     * get full array or a single string of data based on the given filename and key
     * @param string $filename desired file name to get the data
     * @param string|null $key Key of the key pair value of the data
     * @return array|mixed Can return array or a single string
     */
    public static function get(string $filename, string $key = null)
    {
        $fileContent = static::getFileContent($filename);
        if($key === null)
        {
            return $fileContent;
        }
        return isset($fileContent[$key]) ? $fileContent[$key] : [];
    }

    /**
     * Get the configuration data from the given filename
     * @param string $filename name of the file
     * @return array
     */
    public static function getFileContent(string $filename) : array
    {
        $fileContent = [];
        try{
            $path = realpath(sprintf(__DIR__.DIRECTORY_SEPARATOR.
                                                   '..'.DIRECTORY_SEPARATOR.'Config'.
                                                   DIRECTORY_SEPARATOR.'%s.php', $filename));
            if(file_exists($path))
            {
                $fileContent = require $path;
            }
        }catch (\Throwable $exception){
            throw new \RuntimeException(
                sprintf('The specified file %s was not found', $filename)
            );
        }
        return $fileContent;
    }
}