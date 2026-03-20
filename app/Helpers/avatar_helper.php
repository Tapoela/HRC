<?php

if (!function_exists('player_avatar')) {

    function player_avatar($photo = null)
    {
        if (!$photo) {
            return base_url('uploads/defaults/avatar.png');
        }

        $path = FCPATH . 'uploads/' . $photo;

        if (!file_exists($path)) {
            return base_url('uploads/defaults/avatar.png');
        }

        return base_url('uploads/' . $photo);
    }

}