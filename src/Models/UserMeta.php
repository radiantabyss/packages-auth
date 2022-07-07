<?php
namespace RA\Auth\Models;

class UserMeta extends BaseModel
{
    public $timestamps = false;
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public function __construct($attributes = []) {
        parent::__construct($attributes);
        $this->table = env('RA_AUTH_TABLE_NAME').'_meta';
    }
}