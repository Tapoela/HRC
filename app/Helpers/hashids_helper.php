<?php

/**
 * Hashids URL Helper
 * 
 * Encodes/decodes numeric IDs to prevent ID enumeration in URLs.
 * Uses a salt from the .env HASHIDS_SALT setting (falls back to APP_KEY).
 */

use Hashids\Hashids;

/**
 * Get the shared Hashids instance.
 */
function _hashids(): Hashids
{
    static $instance = null;

    if ($instance === null) {
        $salt   = env('HASHIDS_SALT') ?: env('encryption.key') ?: 'hrc-default-salt';
        $minLen = (int)(env('HASHIDS_MIN_LENGTH') ?: 8);
        $instance = new Hashids($salt, $minLen);
    }

    return $instance;
}

/**
 * Encode one or more integers to a hash string.
 *
 * @param  int ...$ids
 * @return string
 */
function encode_id(int ...$ids): string
{
    return _hashids()->encode(...$ids);
}

/**
 * Decode a hash string back to an array of integers.
 * Returns an empty array if the hash is invalid.
 *
 * @param  string $hash
 * @return int[]
 */
function decode_id(string $hash): array
{
    return _hashids()->decode($hash);
}

/**
 * Decode a hash and return the first integer, or null if invalid.
 *
 * @param  string $hash
 * @return int|null
 */
function decode_id_single(string $hash): ?int
{
    $decoded = _hashids()->decode($hash);
    return !empty($decoded) ? (int)$decoded[0] : null;
}
