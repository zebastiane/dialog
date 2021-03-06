<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Claus Due <claus@wildside.dk>, Wildside A/S
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Links to a Post inside a Thread using a section anchor
 *
 * @package Dialog
 * @subpackage ViewHelpers
 */
class Tx_Dialog_ViewHelpers_ThreadPostLinkViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var Tx_Extbase_MVC_Web_Routing_UriBuilder
	 */
	protected $uriBuilder;

	/**
	 * @param Tx_Extbase_MVC_Web_Routing_UriBuilder $uriBuilder
	 */
	public function injectUriBuilder(Tx_Extbase_MVC_Web_Routing_UriBuilder $uriBuilder) {
		$this->uriBuilder = $uriBuilder;
	}

	/**
	 * @param Tx_Dialog_Domain_Model_Post $post
	 * @return integer
	 */
	public function render(Tx_Dialog_Domain_Model_Post $post) {
		$this->uriBuilder->setRequest($this->renderingContext->getControllerContext()->getRequest());
		$thread = $this->resolveThread($post);
		$discussion = $thread->getDiscussion();
		$arguments = array();
		if ($discussion) {
			$arguments['discussion'] = $discussion->getUid();
		}
		if ($thread) {
			$arguments['thread'] = $thread->getUid();
		}
		$link = $this->uriBuilder->setSection('p' . $post->getUid())->uriFor('show', $arguments);
		return $link;
	}

	/**
	 * Resolves the Thread recursively from Post
	 *
	 * @param Tx_Dialog_Domain_Model_Post $post
	 * @return Tx_Dialog_Domain_Model_Thread
	 */
	protected function resolveThread(Tx_Dialog_Domain_Model_Post $post) {
		if ($post->getThread()) {
			return $post->getThread();
		} else if ($post->getPost()) {
			return $this->resolveThread($post->getPost());
		}
	}

}
?>
