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

    CONST DIR_UPLOAD_FILE = '/source/configs/file/';

    protected $table = 'source_configs';

    protected $fillable = [
        'source_id',
        'key',
        'type',
        'value',
        'is_edit'
    ];

    public $timestamps = true;
}
