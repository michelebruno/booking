<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @group Settings
 */
class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:api");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Cache::get('autoloaded_settings', function () {

            return Cache::rememberForever('autoloaded_settings', function () {

                $settings = Setting::autoloaded()->get();

                $array = $settings->mapWithKeys(function ($setting) {
                    return [$setting->chiave => $setting->valore];
                })->toArray();

                foreach (Setting::CONFIG_SETTINGS as $chiave => $data) {
                    if (array_key_exists("public", $data) && $data["public"])
                        $array[str_replace(".", "_", $chiave)] = config($chiave);
                }

                return $array;
            });
        });


        $varianti = [];

        $v = app('Tariffe');

        foreach ($v as $variante) {
            $varianti['varianti_tariffe'][$variante->slug] = $variante;
        }


        return response(array_merge($settings, $varianti));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $setting)
    {
        if (!$request->user()->isSuperAdmin())
            throw new AuthorizationException("Solo un admin può cambiare le impostazioni.");


        switch ($setting) {

            case (bool)($config_setting = Setting::getEditableConfig($setting)):
                $data = $request->validate([
                    $setting => array_merge(['required'], $config_setting['validation_rules'])
                ]);

                Setting::editEnvVariable($config_setting["env_key"], $data[$setting]);

                break;

            case "favicon":
                $request->validate([
                    "favicon" => "required|file|mimetypes:image/x-icon"
                ]);

                if (($file = $request->file('favicon') ) && $path = $file->storeAs('public', 'favicon.ico')) {

                    Setting::updateOrCreate(["chiave" => "favicon"], ["valore" => $path]);

                    Cache::delete('autoloaded_settings');

                    return $this->index($request);
                }

                break;

            default:
                throw new NotFoundHttpException();
                break;
        }
    }


    private function updateSetting(Request $request, $setting)
    {
        // TODO una funzione che esegua lo switch presete in $this->update()
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        // TODO
        throw abort(404);
    }
}
