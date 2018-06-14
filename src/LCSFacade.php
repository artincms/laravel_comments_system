<?php
namespace ArtinCMS\LCS;
use Illuminate\Support\Facades\Facade;

class LCSFacade extends Facade
{
	protected static function getFacadeAccessor() {
		return 'LCSC';
	}
}