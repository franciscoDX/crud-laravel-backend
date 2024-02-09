<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'client_name',
        'project_description',
        'deadline',
        'project_status',
        'project_progress',
        'user_id',
    ];

    public function scopeProjectId($query, $projectId){
        if($projectId){
            return $query->where('id', 'LIKE', "%$projectId%");
        }
    }
    public function scopeDateFrom($query, $dateFrom){
        if($dateFrom){
            return $query->where('created_at', '>=', $dateFrom.' 00:00:00');
        }
    }

    public function scopeDateTo($query, $dateTo){
        if($dateTo){
            return $query->where('created_at', '<=', $dateTo.' 23:59:59');
        }
    }
}
