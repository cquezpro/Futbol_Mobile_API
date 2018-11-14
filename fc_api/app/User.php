<?php

namespace App;

use Hootlex\Friendships\Traits\Friendable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @property mixed confirmed_code
 */
class User extends Authenticatable
{

    use HasApiTokens, Notifiable, Notifiable, Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "password",
        "phone",
        "phone_code",
        "provider",
        "provider_id",
        "confirmed",
        "confirmed_code",
        "nick_name",
        "esport_futbol",
    ];

    protected $appends = [
        'full_name',
        'has_preferences',
        'has_general_info',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'confirmed_code', 'confirmed', 'representative_id',
    ];

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.' . $this->id;
    }

    public function routeNotificationForNexmo($notification)
    {
        if ($this->phone_code == 1) {
            return $this->phone;
        } else {
            return $this->phone_code . $this->phone;
        }
    }

    //region Mutator & Accessors
    public function getFullNameAttribute()
    {
        $full_name = $this->first_name . ' ' . $this->last_name;

        return $full_name;
    }

    public function getHasPreferencesAttribute()
    {
        return $this->userPreferences()->exists();
    }

    public function getHasGeneralInfoAttribute()
    {
        return $this->generalInformation()->exists();
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value;
    }

    //endregion

    //region scopes
    public function confirmed()
    {
        $resp = $this->confirmed ? true : false;

        return $resp;
    }
    //endregion

    //region Relations
    public function generalInformation()
    {
        return $this->hasOne(GeneralUserProfile::class);
    }

    public function userProfiles()
    {
        return $this->hasMany(ProfileUser::class);
    }

    public function userPreferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function technicalInformation()
    {
        return $this->hasOne(TechnicalInformation::class);
    }

    public function speakLanguages()
    {
        return $this->belongsToMany(SpeakLanguage::class, 'user_speak_languages');
    }

    public function representative()
    {
        return $this->belongsTo(Self::class, 'representative_id', 'id');
    }

    public function representativeUsers()
    {
        return $this->hasMany(Self::class, 'representative_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(UserImage::class);
    }

    public function videos()
    {
        return $this->hasMany(UserVideo::class);
    }

    public function avatars()
    {
        return $this->hasMany(UserAvatar::class);
    }

    public function avatar()
    {
        return $this->hasOne(UserAvatar::class);
    }

    public function covers()
    {
        return $this->hasMany(UserCover::class);
    }

    public function cover()
    {
        return $this->hasOne(UserCover::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function sharePosts()
    {
        return $this->hasMany(SharePost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'leader_id', 'follower_id')->withTimestamps();
    }

    public function coaches()
    {
        return $this->hasMany(Coaches::class, 'users_id', 'id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'leader_id')->withTimestamps();
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }

    public function frienships()
    {
        return $this->belongsToMany(Frienship::class, 'user_frienship_group');
    }

    public function collaborator()
    {
        return $this->hasMany(Collaborators::class, 'user_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'user_id');
    }

    public function consoles()
    {
        return $this->belongsToMany(Console::class, 'console_users');
    }

    public function games()
    {
        return $this->belongsToMany(Game::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function futboltypes()
    {
        return $this->hasMany(UserFutbolType::class, 'user_id');
    }

    public function gamepositions()
    {
        return $this->hasMany(UserGamePosition::class, 'user_id');
    }

    public function leagues()
    {
        return $this->belongsToMany(League::class);
    }

    //endregion
}
