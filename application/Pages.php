<?php

if (!defined("SELF_CALLED"))
{
    exit('Application not configured.');
}

class Pages
{
	function findStart($limit, $pageRequest) 
	{
		if ((!isset($pageRequest)) || ($pageRequest == "1"))  
		{
			$start = 0;
			$pageRequest = 1;
		}
		else
		{
			$start = ($pageRequest - 1) * $limit;
		}
		
		return $start;
	}

	function findPages($count, $limit)
	{
		$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
		return $pages;
	}
	
	function pageList($curpage, $pages)
	{
		$page_list  = "";
		
		if (($curpage != 1) && ($curpage))
		{
			$page_list .= "  <a href=" . $_SERVER['PHP_SELF'] . "?page=1 class='btn btn-primary'>«</a>";
		}
		
		for ($i = 1; $i <= $pages; $i++)
		{
			if ($i == $curpage)
			{
				$page_list .= "<button type='button' class='btn btn-primary' style='cursor: default;' disabled>" . $i . "</button>";
			}
			else
			{
				$page_list .= "<a href=" . $_SERVER['PHP_SELF'] . "?page=" . $i . " class='btn btn-primary'>" . $i . "</a>";
			}
			$page_list .= " ";
		}

		if (($curpage != $pages) && ($pages != 0))
		{
			$page_list .= "  <a href=" . $_SERVER['PHP_SELF'] . "?page=" . $pages. " class='btn btn-primary'>»</a>";
		}
		return $page_list;
    }
}
?>