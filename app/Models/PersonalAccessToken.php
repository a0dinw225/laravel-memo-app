<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PersonalAccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['tokenable_id', 'tokenable_type', 'name', 'token', 'abilities'];

    public static function createOrUpdate($userId, $tokenName, $plainTextToken)
    {
        return self::updateOrCreate(
            [
                'tokenable_id' => $userId,
                'tokenable_type' => 'App\Models\User',
                'name' => $tokenName
            ],
            [
                'token' => $plainTextToken,
                'abilities' => json_encode(['*'])
            ]
        );
    }
}
