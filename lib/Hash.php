<?php

namespace Mleko\Hash;

class Hash
{
    /**
     * @var resource Hashing Context
     */
    private $context;

    /**
     * @param $algorithm
     * @param int $options
     * @param string|null $key
     */
    public function __construct($algorithm, $options = 0, $key = null)
    {
        $context = @hash_init($algorithm, $options, $key);
        if (false === $context) {
            if (!in_array($algorithm, static::algorithms())) {
                throw new HashException("Unknown hashing algorithm");
            } else {
                throw new HashException();
            }
        }
        $this->context = $context;
    }

    /**
     * @return string[]
     */
    public static function algorithms()
    {
        return hash_algos();
    }

    /**
     * @param string $algorithm
     * @param string $data
     * @param bool|false $raw
     * @return string
     */
    public static function hashString($algorithm, $data, $raw = false)
    {
        $hash = @hash($algorithm, $data, $raw);
        if (false === $hash) {
            throw new HashException();
        }
        return $hash;
    }

    /**
     * @param string $algorithm
     * @param string $filename
     * @param bool|false $raw
     * @return string
     */
    public static function hashFile($algorithm, $filename, $raw = false)
    {
        $hash = @hash_file($algorithm, $filename, $raw);
        if (false === $hash) {
            throw new HashException();
        }
        return $hash;
    }

    /**
     * @return Hash
     */
    public function copy()
    {
        $newHash = clone $this;
        $newHash->context = hash_copy($this->context);
        return $newHash;
    }

    /**
     * @param bool|false $raw
     * @return string
     */
    public function result($raw = false)
    {
        $context = hash_copy($this->context);
        return hash_final($context, $raw);
    }

    /**
     * @param string $filename
     * @param resource|null $context Stream context
     * @return bool
     */
    public function updateFile($filename, $context = null)
    {
        $hash = @hash_update_file($this->context, $filename, $context);
        if (false === $hash) {
            throw new HashException();
        }
        return $hash;
    }

    /**
     * @param resource $handle
     * @param int $length
     * @return int
     */
    public function updateStream($handle, $length = -1)
    {
        $hash = @hash_update_stream($this->context, $handle, $length);
        if (false === $hash) {
            throw new HashException();
        }
        return $hash;
    }

    /**
     * @param string $data
     * @return bool
     */
    public function update($data)
    {
        $hash = @hash_update($this->context, $data);
        if (false === $hash) {
            throw new HashException();
        }
        return $hash;
    }

}