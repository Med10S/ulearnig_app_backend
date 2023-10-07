<?php

namespace App\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    use DefaultDatetimeFormat;
    protected $casts = ['video' => 'json'];
    public function setVideoAttribute($value)
    {
        //the below method json_encode convert the object to json from array
        $this->attributes['video'] = json_encode(array_values($value));
    }
    public function getVideoAttribute($value)
    {
        //the below method json_encode convert the object to json from array
        $resVideo = json_decode($value, true) ?: [];
        if (!empty($resVideo)) {
            foreach ($resVideo as $key => $v) {
                $resVideo[$key]['url'] = $v['url'];
                $resVideo[$key]['thumbnail'] = $v['thumbnail'];
            }
        }
        return $resVideo;
    }
}