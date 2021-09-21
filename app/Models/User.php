<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = "id";
    protected $fillable = ["name", "email"];

    public static function getUsers()
    {
        return json_decode('[
        {
            "id": 1,
            "name": "Alysha Runolfsdottir",
            "email": "mavis.bayer@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 2,
            "name": "Theron Padberg",
            "email": "shana49@example.net",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 3,
            "name": "Rigoberto Corwin",
            "email": "nicolas.berry@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 4,
            "name": "Jermey Rodriguez",
            "email": "jacobi.eugenia@example.net",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 5,
            "name": "Lyric Muller DDS",
            "email": "terry.audrey@example.com",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 6,
            "name": "Stacey Corwin",
            "email": "iaufderhar@example.com",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 7,
            "name": "Juston Marvin",
            "email": "schoen.delfina@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 8,
            "name": "Oral Friesen PhD",
            "email": "barton.zack@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 9,
            "name": "Vern Homenick PhD",
            "email": "adaline.larson@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        },
        {
            "id": 10,
            "name": "Junius Kovacek",
            "email": "larmstrong@example.org",
            "created_at": "2021-09-12T00:00:12.000000Z",
            "updated_at": "2021-09-12T00:00:12.000000Z"
        }
    ]', true);
    }

    public static function findUser($id) {
        $users = self::getUsers();
        $userId = array_search($id, array_column($users, "id"));
        return $users[$userId];
    }
}
