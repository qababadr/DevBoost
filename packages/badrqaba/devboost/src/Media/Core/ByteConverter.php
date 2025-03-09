<?php

namespace BadrQaba\DevBoost\Media\Core;

/**
 * Trait ByteConverter
 * Provides utility methods for converting bytes to larger data units.
 */
trait ByteConverter
{
    /**
     * Converts bytes to kilobytes
     * @param int $bytes
     * @return float
     */
    public function toKilobytes(int $bytes): float
    {
        return $bytes / 1024;
    }

    /**
     * Converts bytes to megabytes
     * @param int $bytes
     * @return float
     */
    public function toMegabytes(int $bytes): float
    {
        return $bytes / (1024 * 1024);
    }

    /**
     * Converts bytes to gigabytes
     * @param int $bytes
     * @return float
     */
    public function toGigabytes(int $bytes): float
    {
        return $bytes / (1024 * 1024 * 1024);
    }

    /**
     * Converts bytes to terabytes
     * @param int $bytes
     * @return float
     */
    public function toTerabytes(int $bytes): float
    {
        return $bytes / (1024 * 1024 * 1024 * 1024);
    }

    /**
     * Converts bytes to petabytes
     * @param int $bytes
     * @return float
     */
    public function toPetabytes(int $bytes): float
    {
        return $bytes / (1024 * 1024 * 1024 * 1024 * 1024);
    }
}
