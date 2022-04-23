<?php

namespace Danonek\Kernel\Providers;

use Danonek\Kernel\Tools\Configurator as Config;

class CachedInfo
{
	public static function checkLastTimeCached($file) : bool
	{
		return (time() - $file['lastCache'] < Config::getvalue('PURGE_CACHE_TIME'));
	}

	public static function cacheRecentPastes() : void
	{
		$DbHelper = new DatabaseHelper();

		$data['pastes'] = $DbHelper->getRecentPastes();
		$data['lastCache'] = time();
		$pastes = [];
		
		if ($data['pastes'])
		{
			foreach ($data['pastes'] as $paste)
			{
				$paste_info = [
					"nickname" => empty($paste['paste_owner_nickname']) ? "A Guest" : $paste['paste_owner_nickname'],
					"title" => $paste['paste_title'],
					"created" => $paste['paste_created'],
					"uid" => $paste['paste_uid'],
					"views" => $paste['paste_views'],
				];
				array_push($pastes, $paste_info);
			}
			$data['pastes'] = $pastes;
		}

		$file = CACHE_DIR . "recent_pastes.json";
		$json_object = json_encode($data, JSON_PRETTY_PRINT);
		file_put_contents($file, $json_object);
	}
}
?>


