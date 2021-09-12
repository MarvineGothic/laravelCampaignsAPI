<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = "campaigns";
    protected $primaryKey = "id";
    protected $fillable = ["campaign_id", "author"];

    public static function addAuthorAndInputs(&$campaign)
    {
        $campaign->author = User::getUsers()[$campaign->author - 1];
        $campaign->inputs = Input::where("campaign_id", "=", $campaign->id)->get();
    }
}
