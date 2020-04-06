<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingController extends Controller
{
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

                foreach (Setting::PUBLIC_DOTENV_VAR_KEYS as $chiave) {
                    $array[$chiave] = env($chiave, null);
                }

                return $array;
            });
        });


        $varianti = Cache::get('varianti_tariffe', function () {

            return Cache::rememberForever('varianti_tariffe', function () {
                $array = [];

                $varianti = app('VariantiTariffe');

                foreach ($varianti as $variante) {
                    $array['varianti_tariffe'][$variante->slug] = $variante;
                }

                return $array;
            });
        });

        return response(array_merge($settings, $varianti));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: AUTHORIZE e tutto il resto
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $setting)
    {
        if ($request->user()->isSuperAdmin())
            throw new AuthorizationException("Solo un admin può cambiare le impostazioni.");

        $request->validate([
            "favicon" => ["sometimes", "file"],
        ]);

        switch ($setting) {

            case key_exists($setting, Setting::EDITABLE_DOTENV_VAR):
                $data = $request->validate([
                    $setting =>  array_merge(['required'], Setting::EDITABLE_DOTENV_VAR[$setting]['validation_rules'])
                ]);

                Setting::editEnvVariable($setting, $data[$setting]);

                break;

            case "favicon":
                $request->validate([
                    "favicon" => "required|file|mimetypes:image/x-icon"
                ]);

                if ($file = $request->file('favicon')) {

                    $path = $file->storeAs('public', 'favicon.ico');

                    Setting::updateOrCreate(["chiave" => "favicon"], ["valore" => "storage/favicon.jpg"]);

                    Cache::delete('autoloaded_settings');

                    return $this->index($request);
                } else {
                    Log::error('Nessun file trovato', ["request" => $request]);
                    abort(400, 'Non è stato mandato il file.');
                }
                break;

            default:
                throw new NotFoundHttpException();
                break;
        }

        Cache::forget('autoloaded_settings');
        return $this->index();
    }


    private function updateSetting(Request $request, $setting)
    {
        // TODO una funzione che esegua lo switch presete in $this->update()
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
