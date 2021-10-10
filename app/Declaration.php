<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Declaration extends Model {
    public const TYPE_GIFT = 'gift';
    public const TYPE_PAY = 'pay';
    // From a member perspective you gift to the biomeat, but from the system's perspective you pay to the biomeat
    public const TYPE_PAY_BIOMEAT = 'pay-biomeat';

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
		return $query->where('declaration_type', '!=', self::TYPE_GIFT);
	}

	public function scopeType($query, string $type)
	{
		return $query->where('declaration_type', '=', $type);
	}

	public function scopeGift($query)
    {
        return $query->where('declaration_type', '=', self::TYPE_GIFT);
    }

	// Query scope for closed declarations
	public function scopeClosed($query)
	{
		return $query->whereNotNull('closed_at');
	}

}
