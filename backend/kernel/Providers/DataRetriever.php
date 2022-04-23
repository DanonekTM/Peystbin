<?php

namespace Danonek\Kernel\Providers;

class DataRetriever
{
	public static function getCachedRecentPastes()
	{
		$cacheFile = json_decode(file_get_contents(CACHE_DIR . "recent_pastes.json"), true);

		if (!CachedInfo::checkLastTimeCached($cacheFile))
		{
			CachedInfo::cacheRecentPastes($cacheFile);
		}

		echo json_encode($cacheFile);
	}
}
?>