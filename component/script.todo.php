<?php
/*
 * @package todo
 * @copyright Copyright (c)2012 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class Com_TodoInstallerScript
{
	/** @var string The component's name */
	protected $_akeeba_extension = 'com_todo';
	
	/** @var array Obsolete files and folders to remove */
	private $akeebaRemoveFiles = array(
		'files'	=> array(
			// FOF 1.x files
			'libraries/fof/controller.php',
			'libraries/fof/dispatcher.php',
			'libraries/fof/inflector.php',
			'libraries/fof/input.php',
			'libraries/fof/model.php',
			'libraries/fof/query.abstract.php',
			'libraries/fof/query.element.php',
			'libraries/fof/query.mysql.php',
			'libraries/fof/query.mysqli.php',
			'libraries/fof/query.sqlazure.php',
			'libraries/fof/query.sqlsrv.php',
			'libraries/fof/render.abstract.php',
			'libraries/fof/render.joomla.php',
			'libraries/fof/render.joomla3.php',
			'libraries/fof/render.strapper.php',
			'libraries/fof/string.utils.php',
			'libraries/fof/table.php',
			'libraries/fof/template.utils.php',
			'libraries/fof/toolbar.php',
			'libraries/fof/view.csv.php',
			'libraries/fof/view.html.php',
			'libraries/fof/view.json.php',
			'libraries/fof/view.php',
			
		),
		'folders' => array(
		)
	);

	/**
	 * Joomla! pre-flight event
	 * 
	 * @param string $type Installation type (install, update, discover_install)
	 * @param JInstaller $parent Parent object
	 */
	public function preflight($type, $parent)
	{
		// Only allow to install on Joomla! 2.5.0 or later with PHP 5.3.0 or later
		if(defined('PHP_VERSION')) {
			$version = PHP_VERSION;
		} elseif(function_exists('phpversion')) {
			$version = phpversion();
		} else {
			$version = '5.0.0'; // all bets are off!
		}
		if(!version_compare(JVERSION, '2.5.0', 'ge')) {
			echo "<p>You need Joomla! 2.5 or later to install this component</p>";
			return false;
		}
		if(!version_compare($version, '5.3.0', 'ge')) {
			echo "<p>You need PHP 5.3 or later to install this component</p>";
			return false;
		}
		return true;
	}
	
	/**
	 * Runs after install, update or discover_update
	 * @param string $type install, update or discover_update
	 * @param JInstaller $parent 
	 */
	function postflight( $type, $parent )
	{
		// Install FOF if required
		$fofStatus = $this->_installFOF($parent);
		
		// Remove obsolete files and folders
		$akeebaRemoveFiles = $this->akeebaRemoveFiles;
		$this->_removeObsoleteFilesAndFolders($akeebaRemoveFiles);
	}
	
	/**
	 * Removes obsolete files and folders
	 * 
	 * @param array $akeebaRemoveFiles 
	 */
	private function _removeObsoleteFilesAndFolders($akeebaRemoveFiles)
	{
		// Remove files
		jimport('joomla.filesystem.file');
		if(!empty($akeebaRemoveFiles['files'])) foreach($akeebaRemoveFiles['files'] as $file) {
			$f = JPATH_ROOT.'/'.$file;
			if (!file_exists($f))
			{
				continue;
			}
			if (!@unlink($f))
			{
				JFile::delete($f);
			}
		}

		// Remove folders
		jimport('joomla.filesystem.file');
		if(!empty($akeebaRemoveFiles['folders'])) foreach($akeebaRemoveFiles['folders'] as $folder) {
			$f = JPATH_ROOT.'/'.$folder;
			if (!JFolder::exists($f))
			{
				continue;
			}
			JFolder::delete($f);
		}
	}
	
	/**
	 * Installs the FOF framework only if the currently installed version on the
	 * user's site is out of date or if there is no version already installed.
	 * 
	 * @param JInstaller $parent
	 * @return array
	 */
	private function _installFOF($parent)
	{
		$src = $parent->getParent()->getPath('source');
		
		// Install the FOF framework
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.utilities.date');
		$source = $src.'/fof';
		if(!defined('JPATH_LIBRARIES')) {
			$target = JPATH_ROOT.'/libraries/fof';
		} else {
			$target = JPATH_LIBRARIES.'/fof';
		}
		$haveToInstallFOF = false;
		if(!JFolder::exists($target)) {
			$haveToInstallFOF = true;
		} else {
			$fofVersion = array();
			if(JFile::exists($target.'/version.txt')) {
				$rawData = JFile::read($target.'/version.txt');
				$info = explode("\n", $rawData);
				$fofVersion['installed'] = array(
					'version'	=> trim($info[0]),
					'date'		=> new JDate(trim($info[1]))
				);
			} else {
				$fofVersion['installed'] = array(
					'version'	=> '0.0',
					'date'		=> new JDate('2011-01-01')
				);
			}
			$rawData = JFile::read($source.'/version.txt');
			$info = explode("\n", $rawData);
			$fofVersion['package'] = array(
				'version'	=> trim($info[0]),
				'date'		=> new JDate(trim($info[1]))
			);

			$haveToInstallFOF = $fofVersion['package']['date']->toUNIX() > $fofVersion['installed']['date']->toUNIX();
		}

		$installedFOF = false;
		if($haveToInstallFOF) {
			$versionSource = 'package';
			$installer = new JInstaller;
			$installedFOF = $installer->install($source);
		} else {
			$versionSource = 'installed';
		}
		
		if(!isset($fofVersion)) {
			$fofVersion = array();
			if(JFile::exists($target.'/version.txt')) {
				$rawData = JFile::read($target.'/version.txt');
				$info = explode("\n", $rawData);
				$fofVersion['installed'] = array(
					'version'	=> trim($info[0]),
					'date'		=> new JDate(trim($info[1]))
				);
			} else {
				$fofVersion['installed'] = array(
					'version'	=> '0.0',
					'date'		=> new JDate('2011-01-01')
				);
			}
			$rawData = JFile::read($source.'/version.txt');
			$info = explode("\n", $rawData);
			$fofVersion['package'] = array(
				'version'	=> trim($info[0]),
				'date'		=> new JDate(trim($info[1]))
			);
			$versionSource = 'installed';
		}
		
		if(!($fofVersion[$versionSource]['date'] instanceof JDate)) {
			$fofVersion[$versionSource]['date'] = new JDate();
		}
		
		return array(
			'required'	=> $haveToInstallFOF,
			'installed'	=> $installedFOF,
			'version'	=> $fofVersion[$versionSource]['version'],
			'date'		=> $fofVersion[$versionSource]['date']->format('%Y-%m-%d'),
		);
	}
}