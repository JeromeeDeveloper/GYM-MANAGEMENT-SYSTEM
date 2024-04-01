<?php

namespace App\Models;

use App\Models\Membership;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'members';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    public function getFullNameAttribute(){
        return $this->firstname . ' ' . $this->lastname;
    }

    // Inside Member model

public function scopeSearch($query, $keyword)
{
    return $query->where(function ($query) use ($keyword) {
        $query->where('firstname', 'like', '%' . $keyword . '%')
              ->orWhere('lastname', 'like', '%' . $keyword . '%');
    });
}
public function check_ins()
{
    return $this->hasMany(CheckIns::class, 'member_id');
}

// Inside Member model
public function membership()
{
    return $this->hasOne(Membership::class);
}

public function scopeExpiredAnnual($query)
{
    return $query->whereHas('membership', function ($q) {
        $q->where('annual_status', 'expired');
    });
}


protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            $latestMember = static::latest()->first();
            $latestId = $latestMember ? $latestMember->id : 0;
            $member->code = now()->format('md') . '-' . str_pad($latestId + 1, 4, '0', STR_PAD_LEFT);
        });

    }
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
