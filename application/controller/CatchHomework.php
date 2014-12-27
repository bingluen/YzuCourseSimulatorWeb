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
}
?>