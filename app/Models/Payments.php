<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'payments';
    protected $guarded = ['id'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function calculateEndDate()
    {
        $paymentFor = $this->payment_for;

        switch ($paymentFor) {
            case 'session':
                return Carbon::now()->endOfDay()->toDateString();
            case 'monthly':
                return Carbon::now()->addMonth()->toDateString();
            case 'bi-monthly':
                return Carbon::now()->addMonths(2)->toDateString();
            case '6-months':
                return Carbon::now()->addMonths(6)->toDateString();
            case '1-year':
                return Carbon::now()->addYear(1)->toDateString();
            case 'Annual-Fee':
                // For Annual-Fee, set the status to inactive and annual_status to active
                return Carbon::now()->addYear(1)->toDateString();
            default:
                return Carbon::now()->addMonth()->toDateString();
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            $membership = Membership::where('member_id', $payment->member_id)->first();

            if ($membership) {
                // Update
                if ($payment->payment_for === 'Annual-Fee') {
                    $membership->annual_start_date = Carbon::now();
                    $membership->annual_end_date = Carbon::parse($membership->annual_end_date)->addYear();
                }else {
                    $membership->start_date = Carbon::now();

                    switch ($payment->payment_for) {

                        case 'session':
                            $membership->end_date = Carbon::parse($membership->end_date)->addDay();
                            break;
                        case 'monthly':
                            $membership->end_date = Carbon::parse($membership->end_date)->addMonth();
                            break;
                        case 'bi-monthly':
                            $membership->end_date = Carbon::parse($membership->end_date)->addMonths(2);
                            break;
                        case '6-months':
                            $membership->end_date = Carbon::parse($membership->end_date)->addMonths(6);
                            break;
                        case '1-year':
                        case 'Annual-Fee':
                            $membership->end_date = Carbon::parse($membership->end_date)->addYear();
                            break;
                        default:
                            $membership->end_date = Carbon::parse($membership->end_date)->addMonth();
                            break;
                    }
                }


                $membership->save();
                // Create
            } else {
                $membership = new Membership();
                $membership->member_id = $payment->member_id;

                if ($payment->payment_for === 'Annual-Fee') {
                    $membership->annual_start_date = Carbon::now();
                    $membership->annual_end_date = $payment->calculateEndDate();
                } else {
                    $membership->start_date = Carbon::now();
                    $membership->end_date = $payment->calculateEndDate();
                }
                $membership->save();
            }
        });
    }

}
