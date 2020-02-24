<?php
class AccountController extends Controller
{
    public function signupAction() {
        return $this->render(array(
            '_token' => $this->generateCsrfToken('account/signup'),
        ));
    }

    public function registerAction()
    {
        if(!$this->request->isPost()){
            $this->forward404();
        }
        $token = $this->request->getPost('_token');
        if(!$this->checkCsrfToken('account/signup', $token)) {
            return $this->reddirect('account/signup');
        }

        $user_name = $this->request->getPost('user_name');
        $password = $this->request->getPost('password');

        $errors = array();

        if(!strlen($user_name)){
            $errors[] = 'ユーザーIDを入力してください。';
        }elseif(!preg_match('/^\w{3,20}$', $user_name)){
            $errors[] = 'ユーザーIDは半角数字及びアンスコを３＾〜２０文字で入力してください';
        }elseif(!$this->db_manager->get('User')->isUniqueUserName($user_name)) {
            $error[] = 'ユーザーIDは既に使用されています';
        }

        if(!strlen($password)) {
            $error[] = 'パスワードを入力しでください';
        } else if (4 > strlen($password) || strlen($password) > 30) {
            $error[] = 'パスワードは４〜３０文字で入力してください';
        }

        if(count($errors) === 0) {
            $this->db_manager->get('User')->insert($user_name, $password);
            $this->session->setAuthenticated(true);
            $user=$this->db_manager->get('User')->fetchByUserName($user_name);
            $this->session->set('user', $user);
            return $this->redirect('/');
        }

        return $this->render(array(
            'user_name' => $user_name,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signup'),
        ), 'signup');
    }
}