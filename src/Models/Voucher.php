<?php

namespace Controlpanel\Vouchers\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Voucher extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'memo',
        'code',
        'credits',
        'uses',
        'expires_at',
    ];

    protected $dates = [
        'expires_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'credits' => 'float',
        'uses' => 'integer'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (Voucher $voucher) {
            $voucher->users()->detach();
        });
    }

    /**
     * Get the amount of times this voucher has been used
     * @return int
     */
    public function getUsedCount(): int
    {
        return $this->users()->count();
    }

    /**
     * Get status of voucher
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if ($this->users()->count() >= $this->uses) return __('USES_LIMIT_REACHED');
        if (!is_null($this->expires_at)) {
            if ($this->expires_at->isPast()) return __('EXPIRED');
        }

        return __('VALID');
    }

    /**
     * Redeem the voucher
     *
     * @param User $user
     * @return float
     */
    public function redeem(User $user): float
    {
        $user->increment('credits', $this->credits);
        $this->users()->attach($user);

        return $this->credits;
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
