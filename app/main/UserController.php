<?php

/**
 * Class UserController
 * 
 * @author Elwin Tamminga <elwintamminga@live.nl>
 */

class UserController extends Base {
    private $profile;
    private $profilecomment;

    function __construct() {
        $this->input = $_POST;
        $this->profile = new User;
        $this->profilecomment = new Comment;
    }

    function loginUser() {
        Session::clear();
        Session::regenerate();
        $user = $this->profile->getUsername($this->input['username']);
        if (!empty($user) && password_verify($this->input['password'], $user['password'])) {
            Session::set('id', $user['id']);
            Session::set('username', $user['username']);
            Response::redirect('/mvc/user');
        } else {
            View::init('partials/header', 'partials/footer')
            ->set('title', 'Log in')
            ->set('type', 'error')
            ->set('msg', 'Username and/or password incorrect.')
            ->make('box');
        }
    }
    
    function logoutUser() {
        Session::clear();
        Session::regenerate();
        Response::redirect('/mvc');
    }
    
    function registerUser() {
        if (!empty($this->input)) {
            $box_msg = [];
            if ($this->profile->usernameExists($this->input['username']))
                array_push($box_msg, ['msg' => 'Username already in use.']);
            if ($this->profile->emailExists($this->input['emailaddress']))
                array_push($box_msg, ['msg' => 'Email address already in use.']);
            if (!empty($box_msg)) {
                View::init('partials/header', 'partials/footer')
                ->set('title', 'Register')
                ->set('loggedUser', Session::get('username'))
                ->set('box_msg', $box_msg)
                ->make('profiles/showRegister');
            } else {
                if (!is_array($box_msg = $this->profile->insert($this->input))) {
                    View::init('partials/header', 'partials/footer')
                    ->set('title', 'Register')
                    ->set('loggedUser', Session::get('username'))
                    ->set('type', 'success')
                    ->set('msg', 'User '.$this->input['username'].' created succesfully!')
                    ->make('box');
                } else {
                    View::init('partials/header', 'partials/footer')
                    ->set('title', 'Register')
                    ->set('loggedUser', Session::get('username'))
                    ->set('box_msg', $box_msg)
                    ->make('profiles/showRegister');
                }
            }
        } else {
            View::init('partials/header', 'partials/footer')
            ->set('title', 'Register')
            ->set('loggedUser', Session::get('username'))
            ->make('profiles/showRegister');
        }
    }
    
    function showUser($params) {
        if ($id = Session::get('id')) {
            $user = $this->profile->get($id);
            $comments = $this->profile->getProfileComments($id);
            if (empty ( $comments )) {
                $comments = "This profile has no commments.";
            } else {
                foreach ( $comments as $p => $comment ) {
                    $comment_user = $this->profile->get ( $comment ['user_id'] );
                    $comments [$p] ['username'] = $comment_user ['username'];
                }
            }
            View::init('partials/header', 'partials/footer')
            ->set('user', $user)
            ->set('comments', $comments)
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->make('profiles/showUser');
        } else {
            View::init('partials/header', 'partials/footer')
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->set('type', 'error')
            ->set('msg', 'You must be logged in to access this page!')
            ->make('box');
        }
    }
    
    function editUser() {
        if ($id = Session::get('id')) {
            $data = $this->input;
            $user = $this->profile->get($id);
            if ($this->profile->emailExists($this->input['emailaddress']) && $this->input['emailaddress'] != $user['emailaddress']) {
                $box_msg = [['msg' => 'Email address already in use.']];
            } else {
                $data['username'] = Session::get('username');
                $result = $this->profile->update($id, $data);
                if (is_array($result)) {
                    $box_msg = $result;
                } else {
                    $box_msg = 'Settings have been successfully updated.';
                }
            }
            $user = $this->profile->get($id);
            $comments = $this->profile->getProfileComments($id);
            if (empty($comments)) {
                $comments = "This profile has no commments.";
            } else {
                foreach ($comments as $p => $comment) {
                    $comment_user = $this->profile->get($comment['user_id']);
                    $comments[$p]['username'] = $comment_user['username'];
                }
            }
            View::init('partials/header', 'partials/footer')
            ->set('user', $user)
            ->set('comments', $comments)
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->set('box_msg', $box_msg)
            ->make('profiles/showUser');
        } else {
            View::init('partials/header', 'partials/footer')
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->set('type', 'error')
            ->set('msg', 'You must be logged in to access this page!')
            ->make('box');
        }
    }
    
    function showProfile($params) {
        $user = $this->profile->get($params['id']);
        if (!empty($user)) {
            $comments = $this->profile->getProfileComments($params['id']);
            if (empty ( $comments )) {
                $comments = "This profile has no commments.";
            } else {
                foreach ( $comments as $p => $comment ) {
                    $comment_user = $this->profile->get ( $comment ['user_id'] );
                    $comments [$p] ['username'] = $comment_user ['username'];
                }
            }
            View::init('partials/header', 'partials/footer')
            ->set('user', $user)
            ->set('title', 'Profile of '.$user['username'])
            ->set('loggedUser', Session::get('username'))
            ->set('comments', $comments)
            ->make('profiles/showProfile');
        } else {
            View::init()->make('error_pages/404.html');
        }
    }
    
    function addUserComment() {
        if ($id = Session::get('id')) {
            $data = $this->input;
            $data['user_id'] = $id;
            $data['profile_id'] = $id;
            $result = $this->profilecomment->insertProfileComment($data);
            if (is_array($result)) {
                $box_msg = $result;
            } else {
                $box_msg = 'Comment has been successfully added.';
            }
            $user = $this->profile->get($id);
            $comments = $this->profile->getProfileComments($id);
            if (empty($comments)) {
                $comments = "This profile has no commments.";
            } else {
                foreach ($comments as $p => $comment) {
                    $comment_user = $this->profile->get($comment['user_id']);
                    $comments[$p]['username'] = $comment_user['username'];
                }
            }
            View::init('partials/header', 'partials/footer')
            ->set('user', $user)
            ->set('comments', $comments)
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->set('box_msg', $box_msg)
            ->make('profiles/showUser');
        } else {
            View::init('partials/header', 'partials/footer')
            ->set('title', 'Your Profile')
            ->set('loggedUser', Session::get('username'))
            ->set('type', 'error')
            ->set('msg', 'You must be logged in to submit a comment!')
            ->make('box');
        }
    }
    
    function addProfileComment($params) {
        $user = $this->profile->get($params['id']);
        if (!empty($user)) {
            if ($id = Session::get('id')) {
                $data = $this->input;
                $data['user_id'] = $id;
                $data['profile_id'] = $user['id'];
                $result = $this->profilecomment->insertProfileComment($data);
                if (is_array($result)) {
                    $box_msg = $result;
                } else {
                    $box_msg = 'Comment has been successfully added.';
                }
                $comments = $this->profile->getProfileComments($params['id']);
                if (empty ( $comments )) {
                    $comments = "This profile has no commments.";
                } else {
                    foreach ( $comments as $p => $comment ) {
                        $comment_user = $this->profile->get ( $comment ['user_id'] );
                        $comments [$p] ['username'] = $comment_user ['username'];
                    }
                }
                View::init('partials/header', 'partials/footer')
                ->set('user', $user)
                ->set('title', 'Profile of '.$user['username'])
                ->set('loggedUser', Session::get('username'))
                ->set('comments', $comments)
                ->set('box_msg', $box_msg)
                ->make('profiles/showProfile');
            } else {
                View::init('partials/header', 'partials/footer')
                ->set('title', 'Your Profile')
                ->set('loggedUser', Session::get('username'))
                ->set('type', 'error')
                ->set('msg', 'You must be logged in to submit a comment!')
                ->make('box');
            }
        } else {
            View::init()->make('error_pages/404.html');
        }
    }
}