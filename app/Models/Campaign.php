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

    public function inputs() {
        return $this->hasMany(Input::class);
    }

    public function addAuthorAndInputs()
    {
        $this->author = User::findUser($this->author);
    }
}
