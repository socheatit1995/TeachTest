<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'core',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function updateScore() {
        $totalAmount = $this->purchases->sum('amount');
        $this->score = $totalAmount / 10;
        $this->save();
    }
}
