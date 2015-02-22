<?php
/**
 * @package		todo
 * @copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 *
 * This file is required because we are mixing XML forms with Blade and plain PHP view templates in the same view. This
 * means that FOF will read the form file (form.form.xml for the edit task) but also try to load a plain or Blade PHP
 * view template file. The convention is to first look for form(.blade).php and then for default(.blade).php. Since we
 * already have a default.blade.php file it will try to load it. Since that file is designed for a browse task it will
 * result in a fatal error. Therefore we need this form.php file to simply echo the rendered XML form.
 */

echo $this->getRenderedForm();