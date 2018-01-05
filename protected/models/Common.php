<?

class Common
{
	public static function RuDate($date)
	{
		return date('d.m.Y', strtotime($date));
	}

	public static function EnDate($date)
	{
		$arr = explode('.', $date);
		return date('Y-m-d', strtotime($arr[2] . '-' . $arr[1] . '-' . $arr[0]));
	}
}