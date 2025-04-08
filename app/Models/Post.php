<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $primaryKey = 'post_id';
    protected $fillable = [
        'post_title',
        'post_content',
        'post_image',
        'post_status',
        'user_id',
        'post_created',
        'post_modify'
    ];
    protected $casts = [
        'post_created' => 'datetime',
        'post_modify' => 'datetime',
    ];

    static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $posts = self::join('users', 'user_id', 'id')->limit($limit);

        if($params) $posts->where($params);
        if($lastId) $posts->where('post_id', '<', $lastId);
        if($id) $posts->where('post_id', $id);

        return $id ? $posts->first() : $posts->get()->all();
    }

    static function submit($id, $param)
    {
        if($id) return self::where('post_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->post_id : false;
    }

}