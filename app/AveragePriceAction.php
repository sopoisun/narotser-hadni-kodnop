<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AveragePriceAction extends Model
{
    protected $fillable = ['type', 'relation_id', 'old_price', 'input_price',
                            'average_with_round', 'action'];
}
