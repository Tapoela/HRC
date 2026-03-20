<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'setting_key',
        'setting_value'
    ];

    protected $returnType = 'array';

    public function getValue($key)
    {
        $row = $this->where('setting_key', $key)->first();
        return $row['setting_value'] ?? null;
    }

    public function setValue($key, $value)
    {
        $exists = $this->where('setting_key', $key)->first();

        if ($exists) {
            return $this->update($exists['id'], [
                'setting_value' => $value
            ]);
        }

        return $this->insert([
            'setting_key'   => $key,
            'setting_value' => $value
        ]);
    }

    public function getSetting($key)
    {
        $row = $this->where('setting_key', $key)->first();

        return $row['setting_value'] ?? null;
    }
}
