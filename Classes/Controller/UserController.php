<?php
namespace AgoraTeam\Agora\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Phillip Thiele <philipp.thiele@phth.de>
 *           Björn Christopher Bresser <bjoern.bresser@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * UserController
 */
class UserController extends ActionController {

	/**
	 * userRepository
	 *
	 * @var \AgoraTeam\Agora\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository = NULL;


	/**
	 * action favoritePosts
	 *
	 * @return void
	 */
	public function favoritePostsAction() {
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User') && $user->getFavoritePosts() !== NULL) {
			$favoritePosts = $user->getFavoritePosts()->toArray();
			$limit = $this->settings['post']['numberOfItemsInFavoritePostsWidget'];

			if($limit < count($favoritePosts)) {
				$favoritePosts = array_slice($favoritePosts, 0, $limit);
				$listPid = $this->settings['listView'];
			}
		}
		$this->view->assign('favoritePosts', $favoritePosts);
		$this->view->assign('listPid', $listPid);
	}

	/**
	 * action observedThreads
	 *
	 * @return void
	 */
	public function observedThreadsAction() {
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')  && $user->getObservedThreads() !== NULL) {
			$observedThreads = $user->getObservedThreads()->toArray();
			$limit = $this->settings['thread']['numberOfItemsInObservedThreadsWidget'];

			if ($limit < count($observedThreads)) {
				$observedThreads = array_slice($observedThreads, 0, $limit);
				$listPid = $this->settings['listView'];
			}
		}
		$this->view->assign('observedThreads', $observedThreads);
		$this->view->assign('listPid', $listPid);
	}

	/**
	 * action listObservedThreads
	 * @retun void
	 */
	public function listObservedThreadsAction() {
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
			$observedThreads = $user->getObservedThreads();
		}
		$this->view->assign('observedThreads', $observedThreads);
	}


	/**
	 * @param \AgoraTeam\Agora\Domain\Model\Thread $thread
	 * @return void
	 */
	public function addObservedThreadAction(\AgoraTeam\Agora\Domain\Model\Thread $thread) {
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
			$user->addObservedThread($thread);
			$this->userRepository->update($user);
		}
		$this->redirect('list', 'Post', 'Agora', array('thread' => $thread));
	}

	/**
	 * @param \AgoraTeam\Agora\Domain\Model\Thread $thread
	 * @return void
	 */
	public function removeObservedThreadAction(\AgoraTeam\Agora\Domain\Model\Thread $thread) {
			// @todo Back to refere redirect
		$user = $this->getCurrentUser();
		$user->removeObservedThread($thread);
		$this->userRepository->update($user);
		$this->redirect('list', 'Post', 'Agora', array('thread' => $thread));
	}

	/**
	 * @param \AgoraTeam\Agora\Domain\Model\Post $post
	 * @return void
	 */
	public function addFavoritePostAction(\AgoraTeam\Agora\Domain\Model\Post $post) {
			// @todo Back to refere redirect
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
			$user->addFavoritePost($post);
			$this->userRepository->update($user);
		}
		$this->redirect('list', 'Post', 'Agora', array('thread' => $post->getThread()));
	}

	/**
	 * @param \AgoraTeam\Agora\Domain\Model\Post $post
	 * @return void
	 */
	public function removeFavoritePostAction(\AgoraTeam\Agora\Domain\Model\Post $post) {
			// @todo Back to refere redirect
		$user = $this->getCurrentUser();
		if (is_a($user, '\AgoraTeam\Agora\Domain\Model\User')) {
			$user->removeFavoritePost($post);
			$this->userRepository->update($user);
		}
		$this->redirect('list', 'Post', 'Agora', array('thread' =>  $post->getThread()));
	}


}