<?php

function CountPrice($data)
{
    if( $data->use_mark_up == 'Tidak' ){
        return $data->harga;
    }else{
        $hpp = 0;
        foreach( $data->detail as $dd ){
            $hpp += ($dd->harga * $dd->qty);
        }

        $markup         = $data->mark_up / 100;
        $laba           = $hpp * $markup;
        $harga          = $hpp + $laba;

        return Pembulatan($harga);
    }
}

function CountSubOrderTotal($data)
{
    $total = 0;

    foreach($data as $d){
        $total += ( $d->harga_jual * $d->qty );
    }

    return $total;
}

function Pembulatan($bilangan, $withNormalize = false)
{
    $bilangan       = round($bilangan);
    $jumBulat       = config('app.bilangan_bulat');
    $sisaBagi       = $bilangan % $jumBulat;
    $jumNormalize   = 0;

    if( $sisaBagi != 0 ){
        $jumNormalize = $jumBulat - $sisaBagi;
    }

    $bilanganNormal = $bilangan + $jumNormalize;

    if( $withNormalize ){
        return [
            'normal'    => $bilanganNormal,
            'bulat'     => $sisaBagi,
        ];
    }

    return $bilanganNormal;
}

function set_active($path, $active = 'active') {
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function ConvertRawQueryToArray($data)
{
    $temp = [];
    foreach($data as $d){
        array_push($temp, (array)$d);
    }
    return $temp;
}

function timeFilter()
{
    return \Carbon\Carbon::create(2016, 03, 20, 00, 00, 00);
}

function setting()
{
    return \App\Setting::first();
}
