<?php

namespace R64\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use R64\Checkout\Contracts\Customer as CustomerContract;

class Customer extends Authenticatable implements CustomerContract
{
    protected $table = 'customers';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'email_confirmed' => 'boolean',
        'password_migrated' => 'boolean',
    ];
    public $timestamps = true;

    use SoftDeletes;
    use Notifiable;
    use HasFactory;

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
