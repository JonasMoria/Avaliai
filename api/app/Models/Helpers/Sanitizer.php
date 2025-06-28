<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;

class Sanitizer extends Model {

    public function sanitizeNumber($number): string {
        if (empty($number)) {
            return '';
        }

        return preg_replace('/[^\d]/', '', $number);
    }

    public function sanitizeName($name): string {
        if (empty($name)) {
            return '';
        }

        $name = trim(preg_replace('/\s+/', ' ', $name));

        return mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
    }
}
