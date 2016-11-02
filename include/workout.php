<?php

if (!isset($_POST["id"]))
	return false;

$workout = intval($_POST["id"]);

$d->query("select * from workouts where id = ? limit 1", [ $workout ]);

$o = $d->get_obj();

if (!isset($o->type))
	return false;

switch ($o->type) {
	case "rounds_reps":
		echo "<input type=\"hidden\" id=\"descr\" value=\"\"/>";
		echo "<select id=\"rounds\">";
		echo "<option value=\"1\">1</option>";
		echo "<option value=\"2\">2</option>";
		echo "<option value=\"3\" selected>3</option>";
		echo "</select><br/>";
		echo "<input type=\"text\" id=\"reps\"/>";

		break;

	case "reps":
	case "value":
		echo "<input type=\"hidden\" id=\"descr\" value=\"\"/>";
		echo "<input type=\"hidden\" id=\"rounds\" value=\"1\">";
		echo "<input type=\"text\" id=\"reps\"/>";

		break;

	case "textarea":
		echo "<input type=\"hidden\" id=\"rounds\" value=\"1\"/>";
		echo "<input type=\"hidden\" id=\"reps\" value=\"1\"/>";
		echo "<textarea id=\"descr\"></textarea>";

		break;

	case "plank":
		echo "<input type=\"hidden\" id=\"descr\" value=\"\"/>";
		echo "<select id=\"rounds\">";
		echo "<option value=\"1\">1</option>";
		echo "<option value=\"2\">2</option>";
		echo "<option value=\"3\">3</option>";
		echo "<option value=\"4\">4</option>";
		echo "<option value=\"5\">5</option>";
		echo "</select><br/>";
		echo "<input type=\"text\" id=\"reps\"/>";

		break;

	default:
		break;
}

?>
<input type="submit" id="save" class="save_btn" data-id="<?php echo $workout; ?>" value="Lisa"/>
