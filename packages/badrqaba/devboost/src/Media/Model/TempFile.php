<?php

namespace BadrQaba\DevBoost\Media\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TempFile extends Model
{
    use HasFactory;

    protected $fillable = [
        "extra",
        "folder",
        "filename"
    ];

    /**
     * get a temp files names
     *
     * @param array filesNames
     * @param array tempFolders
     * @return array TempFile
     */
    public static function getFiles(array $filesNames, array $tempFolders): Collection
    {
        return self::whereIn("filename", $filesNames)
            ->whereIn("folder", $tempFolders)
            ->get();
    }

    /**
     * Delete temp files
     * 
     * @param array filesNames
     * @param array tempFolders
     */
    public static function deleteTempFiles(array $filesNames, array $tempFolders)
    {
        self::whereIn("filename", $filesNames)
            ->whereIn("folder", $tempFolders)
            ->delete();
    }

    /**
     * Get the relative path of the temp file
     * @return string
     */
    public function relativePath(): string
    {
        return "{$this->folder}/{$this->filename}";
    }
}
