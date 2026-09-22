<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['company_id', 'hotel_id','department_id','branch_id', 'store_id', 'user_id', 'code', 'name', 'short_name', 'currency_code', 'designation', 'phone', 'email', 'ac_no', 'ac_branch','basic_salary','house_rent','medical_allowance','others','deducted','increment_percent','increment_amount','total_salary','leave_balance', 'address', 'national_id', 'joining_date', 'type', 'status', 'created_by', 'updated_by', 'deleted_by'];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function department()
    {
        return $this->belongsTo(Ctegory::class, 'department_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }
}
