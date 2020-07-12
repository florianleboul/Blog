<?php

namespace Blog\Framework;

/**
 * 
 */
class View

{
	static public function render(string $view, array $parameters = null, bool $tinyNeeded = false)
	{
		var_dump($tinyNeeded);
		if (isset($parameters)) {
			extract($parameters);
		}
		if ($tinyNeeded) {
			ob_start();
			require __DIR__.'/../View/scripts/tinymce.php';
			$scripts=ob_get_clean();			
		}

		ob_start();
		require __DIR__.'/../View/content/'.$view.'.php';
		$content=ob_get_clean();

		require __DIR__.'/../View/pages/template.php';
	}
}