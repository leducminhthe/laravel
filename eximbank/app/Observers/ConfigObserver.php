<?php

namespace App\Observers;

use App\Models\Config;

class ConfigObserver
{
    /**
     * Handle the Config "created" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function created(Config $config)
    {
        $this->craeteJsonFile();
    }

    /**
     * Handle the Config "updated" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function updated(Config $config)
    {
        $this->craeteJsonFile();
    }

    /**
     * Handle the Config "deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function deleted(Config $config)
    {
        $this->craeteJsonFile();
    }

    /**
     * Handle the Config "restored" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function restored(Config $config)
    {
        //
    }

    /**
     * Handle the Config "force deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function forceDeleted(Config $config)
    {
        $this->craeteJsonFile();
    }
    private function craeteJsonFile(){
        $directory = 'config';
        $storage = \Storage::disk('local');
        $template = Config::select('name','value')->get()->pluck('value','name')->toJson();
        $storage->put($directory . '/tmp.json',$template);
        \Cache::forever('config_variable',$template);
    }
}
