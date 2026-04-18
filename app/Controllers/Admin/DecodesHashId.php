<?php

namespace App\Controllers\Admin;

/**
 * Trait for decoding hashed URL IDs in admin controllers.
 */
trait DecodesHashId
{
    /**
     * Decode a hashed ID segment from the URL.
     * Redirects back with an error if the hash is invalid.
     *
     * @param  string $hash
     * @return int
     */
    protected function decodeHash(string $hash): int
    {
        $id = decode_id_single($hash);

        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return $id;
    }
}
