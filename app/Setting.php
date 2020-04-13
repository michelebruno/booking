<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Mongodb\Eloquent\Model as MongoModel;

class Setting extends MongoModel
{
    protected $connection = "mongodb";

    const CONFIG_SETTINGS = [
        'app.name' => [
            'public' => true,
            'editable' => true,
            'validation_rules' => ['string'],
            'env_key' => 'APP_NAME'
        ],
        'app.env' => [
            'public' => true,
            'editable' => true,
            'validation_rules' => [],
            'env_key' => 'APP_ENV',
        ],
        'app.debug' => [
            'public' => true,
            'editable' => true,
            'validation_rules' => [],
            'env_key' => 'APP_DEBUG',
        ],
        'app.url' => [
            'public' => true,
            'validation_rules' => [],
            'env_key' => 'APP_URL',
        ],
        'log.default' => [
            'validation_rules' => [],
            'env.key' => 'LOG_CHANNEL',
        ],

        'booking.paypal.client.id' => [
            'public' => true,
            'editable' => true,
            'validation_rules' => ['string'],
            'env_key' => 'PAYPAL_CLIENT_ID',
        ],
        'booking.paypal.client.secret' => [
            'editable' => true,
            'validation_rules' => ['string'],
            'env_key' => 'PAYPAL_CLIENT_SECRET',
        ],

        'telescope.enabled' => [
            'public' => true,
            'editable' => true,
            'validation_rules' => ['boolean'],
            'env_key' => 'TELESCOPE_ENABLED',
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
    public function scopeAutoloaded(\Illuminate\Database\Eloquent\Builder $query, bool $true = true)
    {
        return $query->where('autoload', $true);
    }

    public function scopeInstalledFeature(\Illuminate\Database\Eloquent\Builder  $query, string $feature)
    {
        return $query->where("chiave", self::getInstalledFeaturesKey($feature));
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
        try {
            $s = Setting::installedFeature($feature)->firstOrFail();
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
        ]))->save();
    }

    /**
     * Ritrova il progressivo nel formato _progressivo_ . join( "_" , $args )
     *
     * @return \Illuminate\Database\Eloquent\Model|static 
     */
    public static function progressivo(...$args)
    {
        $scope = join("_", $args);

        /**
         * TODO ->lockForUpdate()
         */
        return self::firstOrCreate(['chiave' => '_progressivo_' . $scope], ['valore' => 1, 'autoload' => false]);
    }

    /**
     * Funzione per i progressivi che potrebbero esssere sovrascritti dagli utenti.
     *
     * @param  Closure|array $mustBeFalseFunction
     * @param  mixed $args
     *
     * @return void
     */
    public static function progressivoSicuro($DBprefix, array $mustHaveNoRes,  ...$args)
    {
        $prefix = join('-', $args) . '-';

        $retr = Setting::progressivo($DBprefix, ...$args);

        while ((call_user_func($mustHaveNoRes,  $prefix . $retr->valore))->count()) {
            $retr->increment('valore');
        }

        return $prefix . $retr->valore;
    }

    public static function getEditableConfig(string $chiave)
    {

        $editable_config = array_filter(Setting::CONFIG_SETTINGS, function ($item) {
            return array_key_exists("editable", $item) && $item["editable"];
        });

        if (array_key_exists($chiave, $editable_config))
            return $editable_config[$chiave];

        $corretto = str_replace("_", ".", $chiave);

        if (array_key_exists($corretto, $editable_config))
            return $editable_config[$corretto];

        dd($corretto);
        return false;
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

        $newEnv = preg_replace("/$key=(\"[^\"]*\"|'[^']*'|.*)/", "$key=$value", $prevEnv);

        return file_put_contents($envPath, $newEnv);
    }
}
