<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;

    protected $table = "inputs";
    protected $primaryKey = "id";
    protected $fillable = ["campaign_id", "type", "value"];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public static function validate($inputsData): bool
    {
        $valid = true;
        if (isset($inputsData)) {
            foreach ($inputsData as $inputData) {
                if (empty(trim($inputData["type"])) || empty(trim($inputData["value"]))) {
                    $valid = false;
                }
            }
        }

        return $valid;
    }
}
