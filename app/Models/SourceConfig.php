<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceConfig extends Model
{
    use HasFactory;

    CONST TYPE_STRING = 'string';
    CONST TYPE_NUMBER = 'number';
    CONST TYPE_RADIO = 'radio';
    CONST TYPE_CHECKBOX = 'checkbox';
    CONST TYPE_FILE = 'file';

    CONST DIR_UPLOAD_FILE = '/app/public/source/configs/file/';

    CONST PATH_GET_FILE = '/source/configs/file/';

    CONST IS_GROUP = 1;
    CONST NOT_IS_GROUP = 0;

    protected $table = 'source_configs';

    protected $fillable = [
        'source_id',
        'key',
        'type',
        'value',
        'is_edit',
        'is_group'
    ];

    protected $appends = ['image'];

    public $timestamps = true;

    public function getImageAttribute()
    {
        if ($this->type === self::TYPE_FILE) {
            return $this->value;
        }
        return '';
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function items()
    {
        return $this->hasMany(SourceConfigItem::class, 'config_id');
    }
}
