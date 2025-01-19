<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function prodi()
    {
        return $this->belongsTo('App\Prodi', 'prodi_id', 'id');
    }

    public function level()
    {
        return $this->belongsTo('App\Level', 'level_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Level', 'level_id');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();

        if (is_array($roles)) {
            foreach ($roles as $need_role) {
                if ($this->cekUserRole($need_role)) {
                    return true;
                }
            }
        } else {
            return $this->cekUserRole($roles);
        }
        return false;
    }

    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function cekUserRole($role)
    {
        return (strtolower($role) == strtolower($this->have_role->level)) ? true : false;
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswa', 'username', 'nim');
    }
    public function dosen()
    {
        return $this->hasOne('App\Dosen', 'kode', 'username');
    }
}