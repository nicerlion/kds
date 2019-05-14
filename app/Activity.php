<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Activity extends Model
{
    protected $table = 'activity_log';

    use LogsActivity;

    protected $fillable = ['name', 'text'];
 
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
}
