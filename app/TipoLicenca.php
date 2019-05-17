<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoLicenca extends Model
{
    protected $table = "tipos_licencas";
    
    public function socio()
	{
		return $this->hasMany('App\Socio', 'tipo_licenca', 'code');
	}
}
