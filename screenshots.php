<?

/*
 * Screenshots Page
 *
 */

// set this for position of this file relative
$file_root = ".";

// load libraries
require($file_root."/include/"."incl.php");

// start of html
html_header("ScummVM :: Screenshots");
sidebar_start();

//display welcome table
echo html_round_frame_start("Screenshots","");

?>
	<h1>Screenshots</h1>

<?

$view = $HTTP_GET_VARS['view'];
$offset = $HTTP_GET_VARS['offset'];

// if in single view
if ($view != "")
{
	if (!is_numeric($view))
	{
		echo "<h1>Error: Unknown image ID</h1>";
	}
	else
	{
		echo
		 html_frame_start("Screenshot Viewer","540",2,0,"color0"),
	     html_frame_tr(
				html_frame_td(
					'<img src="'.$file_root.'/screenshots/big_scummvm_'.$view.'.png" '.
						'width=640 height=400 '.
						'alt="Screenshot '.$view.'">',
					'align=center class="color0" style="padding-top: 10px;"'
				)
			),
	     html_frame_tr(
				html_frame_td(
					implode('', file($file_root.'/screenshots/scummvm_'.$view.'.txt')),
					'align=center class="color0" style="padding-bottom: 10px; font-style: italic;"'
				)
			),
	     html_frame_tr(
				html_frame_td(
			   		html_ahref("&nbsp; &lt;&lt; Back",$PHP_SELF."?offset=".$offset,"style='color: white;'"),
					'align=left class="color4"'
				)
			),
	     html_frame_end();
	}
}
else
{

	// Grab list of images from screenshot dir
	// and loop through them and add to $shots array
	$shots = array();
	$images = get_files($file_root."/screenshots","png");
	while (list($key,$image) = each($images))
	{
		if (ereg("big",$image))
		  continue;
		array_push($shots,$image);
	}

	// counter vars
	$total = count($shots);
	$num = 0;
	$where = 0;

	echo html_frame_start("Screenshot Gallery","540",2,0,"color0")."<tr>";

	// loop and display images
	while (list($c,$image) = each($shots))
	{
		// do not show images less than current pos
		if ($offset > $c)
		  continue;

		// display image
		echo html_frame_td(
				   '<table cellpadding="0" cellspacing="0"><tr><td align="center">'.
				   '<a href="'.$PHP_SELF."?view=".$c.'&amp;offset='.$offset.'">'.
				   '<img src="'.$file_root.'/screenshots/scummvm_'.$c.'.png" '.
				   'width=256 height=192 alt="Screenshot '.$c.'"></a>'.
				   '</td></tr><tr><td align="center">'.
				   implode('', file($file_root.'/screenshots/scummvm_'.$c.'.txt')).
				   '</td></tr></table>',
				   'align=center class="color0" style="padding-top: 10px; font-style: italic;"'
				  );

		// count number of images displayed.
		$num++;

		// end row at 2
		if (($num % 2 == 0) && ($num != 1))
		{
			echo "</tr><tr>\n";
		}

		// end at 4
		if ($num == 4)
		{
			$where = $c;
			break;
		}
	}

	echo "</tr>";

	$nextLink = "&nbsp;";
	$nextLink = "&nbsp;";

	// display prev link
	if ($offset)
	{
		$prev = $offset - 4;
		$prevLink = html_ahref("&lt;&lt; Prev 4 Images",$PHP_SELF."?offset=".$prev,"style='color: white;'");

	}

	// display next link
	if (($offset + 4) < $total)
	{
		$next = $where + 1;
		$nextLink = html_ahref("Next 4 Images &gt;&gt;",$PHP_SELF."?offset=".$next,"style='color: white;'");
	}

	echo html_frame_tr(
			    html_frame_td("&nbsp; ".$prevLink,"align=left").
			    html_frame_td($nextLink." &nbsp;","align=right"),
			    "color4"
			  );
	echo html_frame_end();

//end view
}

// close table
echo html_p();
echo html_round_frame_end("&nbsp;");

// end of html
sidebar_end();
html_footer();

// end
?>
