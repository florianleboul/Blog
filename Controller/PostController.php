<?php

namespace Blog\Controller;

use Blog\Framework\Controller;
use Blog\Model\Manager\PostManager;
use Blog\Exceptions\NotEnoughRightsException;
use Blog\Exceptions\TooLargeImageException;
use Blog\Exceptions\MoveImageException;
use Blog\Exceptions\PostNotFoundException;

class PostController extends Controller
{
    
    protected const IMAGE_MAX_SIZE = 10000000;
    protected const ALLOWED_EXTENSIONS = [
        'png',
        'jpg',
        'jpeg',
        'webp'
    ];
    public function display()
    {
        $postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($postId == false) {
            throw new PostNotFoundException($postId);
        }
        $post = (new PostManager($this->config))->getPostById($postId);
        $this->render($this::VIEW_POST, ['post' => $post, "mainTitle"=>$post->getTitle()]);
    }

    public function addPost()
    {
        if (!$this->isAdmin()) {
            if (!$this->isUser()) {
                $this->redirect($this::URL_LOGIN.'&redirect='.urlencode($this::URL_ADDPOST));
            }
            throw new NotEnoughRightsException();
        }

        $validate = filter_input(INPUT_POST, 'validate', FILTER_VALIDATE_INT);
        if (!$validate) {
            $this->render($this::VIEW_ADDPOST);
            return;
        }
        
        $newpost = (new PostManager($this->config))->createFromArray([
            'postTitle'       =>filter_input(INPUT_POST, 'postTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'postChapo'       =>filter_input(INPUT_POST, 'postChapo', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'postContent'     =>filter_input(INPUT_POST, 'postContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            'postPicture'     =>$this->getPicturePath(),
            'userId'          =>$this->session->getAttribute('user')->getId(),
            'userPseudo'      =>$this->session->getAttribute('user')->getPseudo(),
            'userFirstName'   =>$this->session->getAttribute('user')->getFirstName(),
            'userLastName'    =>$this->session->getAttribute('user')->getLastName(),
            'userMailAddress' =>$this->session->getAttribute('user')->getMailAddress()
        ]);
        (new PostManager($this->config))->add($newpost);
        //$this->redirect(self::URL_HOME);
    }

    public function editPost()
    {
        $postId   = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$this->isAdmin()) {
            if (!$this->isUser()) {
                $this->redirect($this::URL_LOGIN.'&redirect='.urlencode($this::URL_EDITPOST.$postId));
            }
            throw new NotEnoughRightsException();
        }

        $validate = filter_input(INPUT_POST, 'validate', FILTER_VALIDATE_INT);
        if (!$validate) {
            $this->render($this::VIEW_EDITPOST, ["post"=>(new PostManager($this->config))->getPostById($postId)]);
            return;
        }

        $postToUpdate = (new PostManager($this->config))->getPostById($postId);
        $postToUpdate->setTitle(filter_input(INPUT_POST, 'postTitle', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $postToUpdate->setChapo(filter_input(INPUT_POST, 'postChapo', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $postToUpdate->setContent(filter_input(INPUT_POST, 'postContent', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        $imgPath = $this->getPicturePath();
        if ($imgPath) {
            $postToUpdate->setPicture($imgPath);
        }

        (new PostManager($this->config))->update($postToUpdate);
        $this->redirect(self::URL_POST.$postId);
    }

    protected function getPicturePath()
    {
        $image = $_FILES['postImage'] ?? null;
        if ($image !== null) {
            $imageDir = '.\images\\';
            $imageName=md5(uniqid());
            $imageExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
            // if (in_array($imageExtension, $this::ALLOWED_EXTENSIONS)) {
            //  throw new \Exception("Extension non authorisée", 1);
            // }
            if ($image['size'] > $this::IMAGE_MAX_SIZE) {
                throw new TooLargeImageException();
            }
            if (!move_uploaded_file($image['tmp_name'], $imageDir.$imageName.'.'.$imageExtension)) {
                throw new MoveImageException();
            }
            return $imageDir.$imageName.'.'.$imageExtension;
        }
        return null;
    }
}
