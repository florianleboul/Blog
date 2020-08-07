<?php

namespace Blog\Controller;
use Blog\Framework\Controller;
use Blog\Model\Manager\CommentManager;
use Blog\Framework\Configuration;
use Blog\Framework\Session;
use Blog\Framework\View;
use Blog\Exceptions\NotEnoughRightsException;
class AdminController extends Controller{

	public function display()
	{
		if (!$this->isAdmin()) {
			if (!$this->isUser()) {
				$this->redirect($this::URL_LOGIN);
			}
			throw new \NotEnoughRightsException();
		}
		$this->render($this::VIEW_ADMIN, [
			'comments' => $this->getAllComments(),
			'token'    => $this->getToken()
		]);
	}

	protected function getAllInvalidComments()
	{
		return CommentManager::getAllInvalidComments();
	}

	protected function getAllComments()
	{
		return (new CommentManager($this->config))->getAllComments();
	}

	protected function getAllUsers()
	{
		return CommentManager::getAllInvalidComments();
	}

	protected function getAdminUsers()
	{
		return CommentManager::getAllInvalidComments();
	}

	protected function getNonAdminUsers()
	{
		return CommentManager::getAllInvalidComments();
	}
}
