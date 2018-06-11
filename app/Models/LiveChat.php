<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class LiveChat extends Eloquent {
    protected $fillable = ['id', 'lastFetchedAt'];
    protected $dates = ['lastFetchedAt'];
}

?>