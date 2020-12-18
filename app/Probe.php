<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Probe extends Model
{
    protected $table = 'probes';

    protected $fillable = [
            'direction',
            'xaxis',
            'yaxis',
    ];
}
