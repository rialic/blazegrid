<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GenerateUuid
{
  protected static function boot ()
  {
        parent::boot();

        static::creating(function($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{str_replace('_id', '_uuid', $model->getKeyName())} = (string) Str::uuid()->toString();
            }
        });
  }

    public function getKeyType ()
    {
        return 'string';
    }
}