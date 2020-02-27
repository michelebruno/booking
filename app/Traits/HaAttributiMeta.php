<?php

namespace App\Traits;

/**
 * Per i modelli con i meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[] $meta
 * 
 */
trait HaAttributiMeta
{
    protected function getMeta($chiave)
    {
        $meta = $this->meta->firstWhere('chiave', $chiave);

        return $meta !== null ? $meta->valore : null;
    }

    protected function setMeta($chiave, $value)
    {
        if ( $value ) return $this->meta()->updateOrCreate( [ 'chiave' => $chiave ], [ 'valore' => $value ] );
    }

    protected function deleteMeta($chiave)
    {
        $this->meta()->where('chiave', $chiave)->delete();
    }

}
