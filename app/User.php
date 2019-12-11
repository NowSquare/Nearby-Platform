<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Czim\Paperclip\Contracts\AttachableInterface;
use Czim\Paperclip\Model\PaperclipTrait;

class User extends Authenticatable implements AttachableInterface
{
    use PaperclipTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referrer_user_id', 'referrer_token', 'name', 'email', 'role', 'active', 'password', 'trial_ends_at', 'reseller_host', 'admin_host', 'locale'
    ];

    protected $casts = [
      'settings' => 'json',
      'meta' => 'json'
    ];

    public function getDates() {
      return array('created_at', 'updated_at', 'last_login', 'last_payout', 'expires', 'trial_ends_at');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Fix for Stapler: https://github.com/CodeSleeve/laravel-stapler/issues/64
     *
     * Get all of the current attributes on the model.
     *
     * @return array
     */
    public function getAttributes()
    {
        return parent::getAttributes();
    }

    public function __construct(array $attributes = array()) {

        $this->hasAttachedFile('avatar', [
            'variants' => [
                'favicon' => '32x32#',
                'logo' => 'x76'
            ]
        ]);

        parent::__construct($attributes);
    }

    public function parentUser() {
      return $this->belongsTo('\App\User', 'parent_id');
    }

    public function childUsers() {
      return $this->hasMany('\App\User', 'parent_id');
    }

    public function hasRole($roles) {
      return in_array($this->role, $roles);
    }

    public function getRole() {
      return ($this->role == null) ? 'user' : $this->role;
    }

    public function deals() {
      return $this->hasMany('\App\Deal');
    }

    public function properties() {
      return $this->hasMany('\App\Property');
    }

    public function businessCards() {
      return $this->hasMany('\App\BusinessCard');
    }

    public function pages() {
      return $this->hasMany('\App\Page');
    }

    public function coupons() {
      return $this->hasMany('\App\Coupon');
    }

    public function rewards() {
      return $this->hasMany('\App\Reward');
    }

    public function videos() {
      return $this->hasMany('\App\Video');
    }

    public function qr_codes() {
      return $this->hasMany('\App\Qr');
    }
}
