<?php
/**
 * @package		todo
 * @copyright	2015 Nicholas K. Dionysopoulos / Akeeba Ltd 
 * @license		GNU GPL version 3 or later
 */

namespace Akeeba\Todo\Site\View\Item;

use FOF30\Container\Container;
use FOF30\View\Compiler\Blade;
use FOF30\View\DataView\Form as DataViewForm;

class Form extends DataViewForm
{
	public function __construct(Container $container, array $config = array())
	{
		// Create a custom Blade behaviour
		$container->blade->extend(function($template, Blade $compiler)
		{
			$pattern = $compiler->createMatcher('date'); // Defines an @date directive with one argument

			$code = '
$1<?php
$dateMatcherArgs = array$2;
if (!isset($dateMatcherArgs[1]) || empty($dateMatcherArgs[1]))
{
	$dateMatcherArgs[1] = \'DATE_FORMAT_LC1\';
}
$dateMatcherJDate = new \JDate($dateMatcherArgs[0]);
$class = ($dateMatcherJDate->toUNIX() < time()) ? \'label label-important\' : \'\';
echo "<span class=\"$class\">" . $dateMatcherJDate->format(\JText::_($dateMatcherArgs[1])) . "</span>";
?>
';

			return preg_replace($pattern, $code, $template);
		});

		parent::__construct($container, $config);
	}

	/**
	 * We need that to show the front-end buttons in all view except read
	 */
	protected function preRender()
	{
		if (in_array($this->task, array('edit', 'add')))
		{
			$this->container->toolbar->setRenderFrontendButtons(true);
		}

		parent::preRender();
	}

}