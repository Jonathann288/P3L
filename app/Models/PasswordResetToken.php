<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
    public $timestamps = false;
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable =
        [
            'email',
            'token',
            'created_at'
        ];
}