<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AveragePriceAction extends Model
{
    protected $fillable = ['type', 'relation_id', 'old_price', 'old_stok', 'input_price',
                            'input_stok', 'average_with_round', 'action'];
}
