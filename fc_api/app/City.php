<?php

namespace App;

use App\Traits\HashIdFields;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * Allowed fields for the creation of new records in a single line.
     * @var array
     */
    protected $fillable = ['name'];
    /**
     * hash_id new attribute that contains the encrypted id of the resource
     * @var array
     */
    protected $appends = [];
    /**
     * hide the unencrypted id to give more security.
     * @var array
     */
    protected $hidden = ['state_id'];

    //region Relations

    /**
     * Method that returns the states related to the city.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Method that returns the Users related to the City.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    //endregion

    public function collaborator()
    {
        return $this->hasOne(Collaborators::class);
    }
}
