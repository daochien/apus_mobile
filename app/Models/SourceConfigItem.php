<?php

namespace App\Models;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceConfigItem extends Model
{
    use HasFactory;

    CONST TYPE_STRING = 'string';
    CONST TYPE_NUMBER = 'number';
    CONST TYPE_RADIO = 'radio';
    CONST TYPE_CHECKBOX = 'checkbox';
    CONST TYPE_FILE = 'file';

    CONST DIR_UPLOAD_FILE = '/source/configs/file/';

    protected $fillable = [
        'config_id',
        'key',
        'type',
        'value',
        'is_edit'
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

}
