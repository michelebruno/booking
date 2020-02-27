<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserMeta
 *
 * @property int $id
 * @property int $user_id
 * @property string $chiave
 * @property string $valore
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereChiave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereValore($value)
 * @mixin \Eloquent
 */
class UserMeta extends Model
{
    protected $table = 'users_meta';

    public $timestamps = false;

    public $fillable = [
        'chiave',
        'valore'
    ];

    public function user()
    {
        $this->belongsTo('App\User');
    }
    
}
