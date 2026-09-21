<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $table = 'hrm_hotels';
    protected $fillable = ['name', 'phone', 'address', 'entry_date', 'status', 'created_by', 'updated_by'];

}
