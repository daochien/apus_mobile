<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    CONST DIR_UPLOAD_SOURCE = 'app/public/sources/';

    CONST DIR_UPLOAD_IMAGE = 'app/public/images/';

    protected $table = 'sources';

    protected $fillable = [
        'code',
        'name',
        'avatar',
        'path',
        'status',
        'desc'
    ];

    public $timestamps = true;

    protected static function booted()
    {
//        static::creating(function ($model) {
//            $model->code = Hashids::encode($model->id);
//        });
        static::created(function ($model) {
            $model->code = strtoupper(Hashids::encode($model->id));
            $model->save();
        });
    }

    public function getAvatarAttribute($value)
    {
        return !empty($value) ? FileHelper::getLink($value, "/images/") : '';
    }

    public function configs()
    {
        return $this->hasMany(SourceConfig::class, 'source_id');
    }

}
