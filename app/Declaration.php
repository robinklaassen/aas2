<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Declaration extends Model {

	protected $guarded = ['id', 'created_at', 'updated_at'];
	
	// Carbon dates
	protected $dates = ['created_at', 'updated_at', 'closed_at', 'date'];
	
	// A declaration belongs to one member
	public function member()
	{
		return $this->belongsTo('App\Member');
	}

	public function getIsClosedAttribute(): bool
	{
		return $this->closed_at !== null;
	}
	
	// Query scope for open declarations
	public function scopeOpen($query)
	{
		return $query->whereNull('closed_at');
	}

	public function scopeBillable($query)
	{
		return $query->where('gift', '=', '0');
	}
	
	// Query scope for closed declarations
	public function scopeClosed($query)
	{
		return $query->whereNotNull('closed_at');
	}
	
}