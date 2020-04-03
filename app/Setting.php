<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    const PUBLIC_DOTENV_VAR_KEYS = [
        'APP_NAME',
        'APP_ENV',
        'APP_DEBUG',
        'APP_URL',
        'LOG_CHANNEL',

        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME',

        'AWS_ACCESS_KEY_ID',
        'AWS_DEFAULT_REGION',
        'AWS_BUCKET',

        'PAYPAL_CLIENT_ID',

        'TELESCOPE_ENABLED',
    ];

    const EDITABLE_DOTENV_VAR = [
        'APP_NAME' => [
            'validation_rules' => ['string']
        ],
        'APP_ENV' => [
            'validation_rules' => []
        ],
        'APP_DEBUG' => [
            'validation_rules' => []
        ],
        'APP_URL' => [
            'validation_rules' => []
        ],
        'LOG_CHANNEL' => [
            'validation_rules' => []
        ],

        'DB_CONNECTION' => [
            'validation_rules' => []
        ],
        'DB_HOST' => [
            'validation_rules' => []
        ],
        'DB_PORT' => [
            'validation_rules' => []
        ],
        'DB_DATABASE' => [
            'validation_rules' => []
        ],
        'DB_USERNAME' => [
            'validation_rules' => []
        ],
        'DB_PASSWORD' => [
            'validation_rules' => []
        ],

        'BROADCAST_DRIVER' => [
            'validation_rules' => []
        ],
        'CACHE_DRIVER' => [
            'validation_rules' => []
        ],
        'QUEUE_CONNECTION' => [
            'validation_rules' => []
        ],
        'SESSION_DRIVER' => [
            'validation_rules' => []
        ],
        'SESSION_LIFETIME' => [
            'validation_rules' => []
        ],

        'REDIS_HOST' => [
            'validation_rules' => []
        ],
        'REDIS_PASSWORD' => [
            'validation_rules' => []
        ],
        'REDIS_PORT' => [
            'validation_rules' => []
        ],

        'MAIL_DRIVER' => [
            'validation_rules' => []
        ],
        'MAIL_HOST' => [
            'validation_rules' => []
        ],
        'MAIL_PORT' => [
            'validation_rules' => []
        ],
        'MAIL_USERNAME' => [
            'validation_rules' => []
        ],
        'MAIL_PASSWORD' => [
            'validation_rules' => []
        ],
        'MAIL_ENCRYPTION' => [
            'validation_rules' => []
        ],

        'MAIL_FROM_ADDRESS' => [
            'validation_rules' => []
        ],
        'MAIL_FROM_NAME' => [
            'validation_rules' => []
        ],

        'AWS_ACCESS_KEY_ID' => [
            'validation_rules' => []
        ],
        'AWS_SECRET_ACCESS_KEY' => [
            'validation_rules' => []
        ],
        'AWS_DEFAULT_REGION' => [
            'validation_rules' => []
        ],
        'AWS_BUCKET' => [
            'validation_rules' => []
        ],

        'PUSHER_APP_ID' => [
            'validation_rules' => []
        ],
        'PUSHER_APP_KEY' => [
            'validation_rules' => []
        ],
        'PUSHER_APP_SECRET' => [
            'validation_rules' => []
        ],
        'PUSHER_APP_CLUSTER' => [
            'validation_rules' => []
        ],

        'MIX_PUSHER_APP_KEY' => [
            'validation_rules' => []
        ],
        'MIX_PUSHER_APP_CLUSTER' => [
            'validation_rules' => []
        ],

        'PAYPAL_CLIENT_ID' => [
            'validation_rules' => ['string']
        ],
        'PAYPAL_CLIENT_SECRET' => [
            'validation_rules' => ['string']
        ],

        'TELESCOPE_ENABLED' => [
            'validation_rules' => ['boolean']
        ],

    ];

    protected $fillable = [
        'chiave', 'valore', 'autoload'
    ];

    protected $attributes = [
        'autoload' => false
    ];

    protected $primaryKey = "chiave";

    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        self::created(function () {
            Cache::forget('autoloaded_settings');
        });

        self::updated(function () {
            Cache::forget('autoloaded_settings');
        });

        self::deleted(function () {
            Cache::forget('autoloaded_settings');
        });
    }
    /**
     * 
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $true
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function scopeAutoloaded($query, bool $true = true)
    {
        return $query->where('autoload', $true);
    }

    public static function getInstalledFeaturesKey(string $feature)
    {

        return "__installed_feature_" . preg_replace("/([\"'\s\t\n\r]+)/", "_", $feature);
    }

    /**
     * Imposta la funzionalità come installata.
     *
     * @param string $feature
     * @param boolean $returnModel
     * @return bool|self|string
     */
    public static function isFeatureInstalled(string $feature, bool $returnModel = false)
    {
        $key = self::getInstalledFeaturesKey($feature);

        try {
            $s = Setting::whereChiave($key)->firstOrFail();
            return $returnModel ? $s : $s->valore;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $th) {
            return false;
        }
    }

    public static function setFeatureAsInstalled(string $feature)
    {
        $key = self::getInstalledFeaturesKey($feature);

        return (new self([
            "chiave" => $key,
            "valore" => true,
            "autoload" => false
        ]))->saveOrFail();
    }

    /**
     * Ritrova il progressivo nel formato _progressivo_ . join( "_" , $args )
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public function scopeProgressivo($query, ...$args)
    {
        $scope = join("_", $args);

        /**
         * TODO ->lockForUpdate()
         */
        return $query->firstOrCreate(['chiave' => '_progressivo_' . $scope], ['valore' => 1, 'autoload' => false]);
    }

    /**
     * Funzione per i progressivi che potrebbero esssere sovrascritti dagli utenti.
     *
     * @param  Builder $query
     * @param  Closure|array $mustBeFalseFunction
     * @param  mixed $args
     *
     * @return void
     */
    public function scopeProgressivoSicuro(Builder $query, $DBprefix, array $mustHaveNoRes,  ...$args)
    {
        $prefix = join('-', $args) . '-';

        $retr = Setting::progressivo($DBprefix, ...$args);

        while ((call_user_func($mustHaveNoRes,  $prefix . $retr->valore))->count()) {
            $retr->increment('valore');
        }

        return $prefix . $retr->valore;
    }

    public static function editEnvVariable(string $key, string $value)
    {
        $key = strtoupper($key);

        $value = preg_replace("/([\n\r]+)/", "", $value);

        if (preg_match("/([\"'\s\t\n\r]+)/", $value)) { // Ha bisogno di avere delle virgolettato

            $value = addslashes($value);
            $value = "\"$value\"";
        }

        $envPath = base_path() . '/.env';

        $prevEnv = file_get_contents($envPath);

        $newEnv = preg_replace("/$key=(\"[^\"]*\"|'[^']*'|.*)/g", "$key=$value", $prevEnv);

        return file_put_contents($envPath, $newEnv);
    }
}
