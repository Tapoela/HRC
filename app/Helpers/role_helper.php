<?php

if (! function_exists('roleId')) {
    function roleId(): int
    {
        return (int) session()->get('role_id');
    }
}

if (! function_exists('isAdmin')) {
    function isAdmin(): bool
    {
        return roleId() === 1;
    }
}

if (! function_exists('isCoach')) {
    function isCoach(): bool
    {
        return roleId() === 2;
    }
}

if (! function_exists('isPlayer')) {
    function isPlayer(): bool
    {
        return roleId() === 3;
    }
}

if (! function_exists('hasRole')) {
    function hasRole(array $roles): bool
    {
        return in_array(roleId(), $roles);
    }
}
