<?php

namespace App\Traits;

use Illuminate\Support\Arr;

/**
 * Per i modelli con i meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[] $meta
 * 
 */
trait HaAttributiMeta
{
    private $doNotAppend = [];

    protected function getMeta($chiave, $willFakeAttribute = true)
    {
        $meta = $this->meta->firstWhere('chiave', $chiave);

        return $meta !== null ? $meta->valore : null;
    }

    protected function setMeta($chiave, $value)
    {
        if ($value) return $this->meta()->updateOrCreate(['chiave' => $chiave], ['valore' => $value]);
    }

    protected function deleteMeta($chiave)
    {
        $this->meta()->where('chiave', $chiave)->delete();
    }

    public function toArray()
    {
        $array = parent::toArray();

        $array['meta'] = $this->meta->mapWithKeys(function ($item) {
            return [ $item->chiave => $item->valore ];
        });

        Arr::forget($array['meta'], $this->doNotAppend);

        return $array;
    }
}
