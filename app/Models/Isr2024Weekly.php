<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Percentage;

class Isr2024Weekly extends Model
{
    protected $table = 'isr_2024_weekly';
    protected $fillable = [
        'rank	',
        'limif',
        'limsu',
        'cuota',
        'porcen'
    ];

}
