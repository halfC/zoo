<?php 
/**
 * 
 * 当你使用Microsoft Word会创建许多Tag，比如font，span，style，class等。
 * 这些标签对于Word本身而言是非常有用的，但是当你从Word粘贴至网页时，你会发现很多无用的Tag。
 * 因此，下面的这段代码可帮助你删除所有无用的Word HTML Tag
 */

function cleanHTML($html) {
	/// <summary>
	/// Removes all FONT and SPAN tags, and all Class and Style attributes.
	/// Designed to get rid of non-standard Microsoft Word HTML tags.
	/// </summary>
	// start by completely removing all unwanted tags

	$html = ereg_replace("<(/)?(font|span|del|ins)[^>]*>","",$html);

	// then run another pass over the html (twice), removing unwanted attributes

	$html = ereg_replace("<([^>]*)(class|lang|style|size|face)=("[^"]*"|'[^']*'|[^>]+)([^>]*)>","<\1>",$html);
	$html = ereg_replace("<([^>]*)(class|lang|style|size|face)=("[^"]*"|'[^']*'|[^>]+)([^>]*)>","<\1>",$html);

	return $html
}



?>