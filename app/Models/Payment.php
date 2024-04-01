<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Miladimos\Toolkit\Traits\HasUUID;

class Payment extends Model
{
    use HasUUID, SoftDeletes;

    // protected $fillable = ['user_id', 'resnumber', 'course_id', 'price', 'payment', 'uuid'];

    protected $table = 'payments';

    protected $guarded = [];

    public function paymentorable(): MorphTo
    {
        return $this->morphTo();
    }

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function for()
    {
        if ($this->type == PaymentTypeEnum::WALLET_CHARGE) {
            return 'شارژ کیف پول';
        } else {
            $course = $this->paymentable;

            return 'خرید دوره : '.$course->title;
        }
    }

    public function type()
    {
        switch ($this->type) {
            case PaymentTypeEnum::WALLET_CHARGE:
                return 'شارژ کیف پول';
                break;
            case PaymentTypeEnum::BUY:
                return 'خرید مستقیم';
                break;
            case PaymentTypeEnum::BUY_FROM_WALLET:
                return 'پرداخت از کیف پول';
                break;
            default:
                // code...
                break;
        }
    }

    public function status()
    {
        switch ($this->status) {
            case PaymentStatusEnum::SUCCESS:
                return 'موفق';
                break;
            case PaymentStatusEnum::FAILED:
                return 'ناموفق';
                break;
            default:
                // code...
                break;
        }
    }
}
