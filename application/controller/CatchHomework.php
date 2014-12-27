<?php
class CatchHomework extends Controller
{
	public function index()
	{
		$this->loadView('_templates/header');
		$catcher = $this->loadModel('getHomework');
		$catcher->executeCatch(array('user' => '', 'pass' => ''));
		$data = $catcher->getHomeworkList();
		$this->loadView('catchhomework/index', $data);
		$this->loadView('_templates/footer');
	}

/*
	public function API($APIUserId = 0, $APIToken = 0, $userData)
	{
		$AuthAPI = $this->loadModel('AuthAPI');

		try {
			$AuthAPI->AuthAPIUser('catch_homework', $APIUserId, $APIToken);
			openssl_private_decrypt($userData['user'], $userDataDecrypted['user'], $AuthAPI->getPrivateKey())
			openssl_private_decrypt($userData['pass'], $userDataDecrypted['pass'], $AuthAPI->getPrivateKey())
			$catcher = $this->loadModel('getHomework');
			$catcher->executeCatch(array('user' => $userDataDecrypted['user'], 'pass' => $userDataDecrypted['pass']));
			$data = $catcher->getHomeworkList();
			openssl_public_encrypt(json_encode($data), $dataCrypted, $AuthAPI->getPublicKey());
			echo $dataCrypted;
			exit;
		} catch (Exception $e) {
			echo json_encode($e->getMessage());
			exit;
		}

	}
	*/

}
?>