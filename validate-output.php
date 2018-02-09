<?php
/*
 * Copyright (c) 2018 Tuukka Norri
 * This code is licensed under MIT license (see LICENSE for details).
 */

namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class ValidateOutputPlugin
 * @package Grav\Plugin
 */
class ValidateOutputPlugin extends Plugin
{
	protected $validateOutput = false;
	
	
	/**
	 * @return array
	 *
	 * The getSubscribedEvents() gives the core a list of events
	 * that the plugin wants to listen to. The key of each
	 * array section is the event that the plugin listens to
	 * and the value (in the form of an array) contains the
	 * callable (or function) as well as the priority. The
	 * higher the number the higher the priority.
	 */
	public static function getSubscribedEvents()
	{
		return [
			'onPluginsInitialized' => ['onPluginsInitialized', 0]
		];
	}

	/**
	 * Initialize the plugin
	 */
	public function onPluginsInitialized()
	{
		// Don't proceed if we are in the admin plugin.
		if ($this->isAdmin())
		{
			return;
		}

		// Enable the main event we are interested in with a low priority.
		$this->enable([
			'onBuildPagesInitialized'	=>	['onBuildPagesInitialized', 0],
			'onOutputGenerated'			=>	['onOutputGenerated', -255]
		]);
	}
	
	
	public function onBuildPagesInitialized(Event $e)
	{
		// Try to determine if the output should be processed (i.e. not cached).
		$this->validateOutput = true;
	}
	
	
	/**
	 * Parse the HTML and report errors if needed.
	 *
	 * @param Event $e
	 */
	public function onOutputGenerated(Event $e)
	{
		if ($this->validateOutput)
		{
			$this->validateOutput = false;
			
			// Get the current HTML content.
			$content = $this->grav->output;
		
			// Get the configuration.
			$useDom = $this->config->get('plugins.validate-output.use_dom_document');
			$useTidy = $this->config->get('plugins.validate-output.use_tidy');
			
			if ($useDom)
			{
				// Create a DOM from the given content and report parsing errors if needed.
				// Do not use a schema for now.
				$dom = new \DOMDocument();
				$dom->encoding = "UTF-8";
			
				try
				{
					$html = '<?xml encoding="utf-8" ?>' . $content; // XML encoding needed to treat the input as UTF-8.
					$result = $dom->loadHTML($html, LIBXML_HTML_NODEFDTD);
					if (!$result)
					{
						$uri = $this->grav['uri'];
						$this->grav['log']->warning(sprintf("Validate Output: Unable to parse the document for %s with Tidy.", $uri));
					}
				}
				catch (\Exception $exc)
				{
					$uri = $this->grav['uri'];
					$msg = sprintf("Validate Output: DOMDocument reported the following errors for %s: %s", $uri, $exc->getMessage());
					$this->grav['log']->warning($msg);
				}
			}
		
			if ($useTidy)
			{
				// Validate the document with Tidy's default options and report the errors if needed.
				$config = [];
				$tidy = new \tidy();
				$tidy->parseString($content, $config, 'utf8');
				$st = $tidy->cleanRepair();
				if (!$st)
				{
					$uri = $this->grav['uri'];
					$this->grav['log']->warning(sprintf("Validate Output: Unable to parse the document for %s with Tidy.", $uri));
					goto end_tidy;
				}
			
				$result = $tidy->getStatus();
				if (0 != $result)
				{
					$uri = $this->grav['uri'];
					$errors = $tidy->errorBuffer;
					$msg = sprintf("Validate Output: Tidy reported the following errors for %s: %s", $uri, $errors);
					$this->grav['log']->warning($msg);
				}
			end_tidy:
				;
			}
		}
	}
}
