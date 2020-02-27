<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $settings = Cache::get('autoloaded_settings', function () {
            return Cache::rememberForever('autoloaded_settings', function () {
                    
                $settings = Setting::autoloaded()->get();
        
                $array = [];
        
                foreach ($settings as $setting ) {
                    $array[$setting->chiave] = $setting->valore;
                }

                return $array;
            });
        });


        $varianti = Cache::get('varianti_tariffe', function () {

            return Cache::rememberForever('varianti_tariffe', function ()
            {
                $array = [];
    
                $varianti = app('VariantiTariffe');
    
                foreach ($varianti as $variante) {
                    $array['varianti_tariffe'][$variante->slug] = $variante;
                }
                
                return $array;
            });

        });

        return response( array_merge($settings , $varianti) );
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
        switch ($setting) {
            case "favicon" : 
                $request->validate([
                    "favicon" => "required|file|mimetypes:image/x-icon"
                ]);
                
                if ( $file = $request->file('favicon') ) {

                    $path = $file->storeAs('public', 'favicon.ico' );

                    Setting::updateOrCreate(["chiave" => "favicon" ], [ "valore" => "storage/favicon.jpg" ]);
                    Cache::delete('autoloaded_settings');
                    return $this->index($request);
                        
                } else {
                    Log::error('Nessun file trovato', [ "request" => $request ]);
                    abort(500, 'Non è stato mandato il file.');
                }
                break;

            default:
                Cache::forget('autoloaded_settings');
                Setting::updateOrCreate( [ "chiave" => $setting ] , [ 'valore' => $request->input($setting) , 'autoload' => true ] );
                return $this->index($request);
                break;
        }
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
