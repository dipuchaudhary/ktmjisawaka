<?php

namespace App\Models;
use App\Models\AviyogChallani;

use Illuminate\Database\Eloquent\Model;

class AviyogChallani extends Model
{
    protected $fillable = [
        'challani_date',
        'challani_number',
        'jaherwala_name',
        'pratiwadi_name',
        'mudda_name',
        'gender',
        'mudda_number',
        'sarkariwakil_name',
        'anusandhan_garne_nikaye',
        'faat_name',
        'kaifiyat',
        'created_at',
    ];
    // protected static function booted()
    // {
    //     // INSERT into AviyogChallani when PatraChallani is created
    //     $exists = AviyogChallani::where('id', $data->id)->exists();
    //     if (!$exists) {
    //         static::created(function ($patra) {
    //             AviyogChallani::create([
    //                 'challani_date'            => '',
    //                 'challani_number'          => $patra->challani_number,
    //                 'jaherwala_name'           => $patra->jaherwala_name,
    //                 'pratiwadi_name'           => $patra->pratiwadi_name,
    //                 'mudda_name'               => $patra->mudda_name,
    //                 'gender'                   => '',
    //                 'mudda_number'             => $patra->mudda_number,
    //                 'sarkariwakil_name'        => $patra->sarkariwakil_name,
    //                 'faat_name'                => $patra->faat_name,
    //                 'anusandhan_garne_nikaye'  => $patra->anusandhan_garne_nikaye,
    //                 'kaifiyat'                 => '',
    //             ]);
    //         });
    //     }

    //     // UPDATE corresponding AviyogChallani when PatraChallani is updated
    //     static::updated(function ($patra) {
    //         AviyogChallani::where('id', $patra->id)->update([
    //             'challani_date'            => '',
    //             'challani_number'          => $patra->challani_number,
    //             'jaherwala_name'           => $patra->jaherwala_name,
    //             'pratiwadi_name'           => $patra->pratiwadi_name,
    //             'mudda_name'               => $patra->mudda_name,
    //             'gender'                   => $patra->gender,
    //             'mudda_number'             => $patra->mudda_number,
    //             'sarkariwakil_name'        => $patra->sarkariwakil_name,
    //             'faat_name'                => $patra->faat_name,
    //             'anusandhan_garne_nikaye'  => $patra->anusandhan_garne_nikaye,
    //             'kaifiyat'                 => $patra->kaifiyat,
    //         ]);
    //     });

    //     // DELETE corresponding AviyogChallani when PatraChallani is deleted
    //     static::deleted(function ($patra) {
    //         AviyogChallani::where('id', $patra->id)->delete();
    //     });
    // }

}
