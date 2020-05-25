<?php

namespace Blog\Controller;

// require __DIR__.'/../Framework/Controller.php';

use Blog\Framework\Controller;
use Blog\Model\Manager\PostManager;
use Blog\Model\Manager\CommentManager;

class PostController extends Controller{
	
	static public function displayPostById(int $id)
	{
		if ($id<0) {
			throw new OutOfRangeException("L'ID d'un article ne peut pas être négatif");
		}
		
		$result = PostManager::getPostById($id);
		$commentsList = self::getComments($id)

		//View::render('post', 'post', ['comments'])
		require __DIR__.'/../View/post/post.php';
	}

	static public function display(){

	}

	static protected function getComments(int $postId)
	{
		return CommentManager::getCommentsByPost($postId);
	}

}