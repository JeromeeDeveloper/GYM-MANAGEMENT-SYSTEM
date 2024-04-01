<?php
namespace App\Models;

use App\Models\Membership;
use App\Models\Member;
use App\Models\Payments;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'membership';
    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value); // Capitalize first letter of status
    }

    public function getAnnualStatusAttribute($value)
    {
        return ucfirst($value); // Capitalize first letter of annual_status
    }



    protected static function booted()
    {
        static::saving(function ($membership) {
            if ($membership->annual_end_date !== null) {
                if ($membership->annual_end_date > now()) {
                    $membership->annual_status = 'active';
                } else {
                    $membership->annual_status = 'cancelled';
                }
            } else {
                // If annual_end_date is null, set annual_status to null
                $membership->annual_status = null;
            }

            if ($membership->end_date !== null) {
                if ($membership->end_date > now()) {
                    $membership->status = 'active';
                } else {
                    $membership->status = 'expired';
                }
            } else {
                // If end_date is null, set status to null
                $membership->status = null;
            }

        });
    }
}
