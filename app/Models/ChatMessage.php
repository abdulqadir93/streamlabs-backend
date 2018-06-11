<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class ChatMessage extends Eloquent {
    protected $fillable = [
        'id',
        'chatId',
        'publishedAt',
        'hasDisplayContent',
        'displayMessage',
        'author'
    ];
}

?>