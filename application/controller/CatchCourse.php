<?php
class CatchCourse extends Controller
{
	public function index()
	{
		$this->loadView('_templates/header');
		$this->loadView('catchcourse/index');
		$this->loadView('_templates/footer');
	}

	
	//public function API($app = 0, ...$department) 要5.6才支援，因此先不開一次查多系所
	public function API($app = '0', $department = 0, $grade = 0)
	{
		switch($app)
		{
			case "CatchCourse":
				echo json_encode($this->APICatchCourse($grade, $department));
				break;
			case "0":
				echo json_encode('Error: Did NOT Assgin App.');
				break;
			default:
				echo json_encode('Error: app did NOT exist.');
		}
	}

	//暫時只支援1032的課程，之後把model裡的parser改用DOM重寫應該就可以支援所有學期
	private function APICatchCourse($grade = 0 , $department = 0, $year = 103, $semester = 2)
	{
		$legalDepartment = [300, 302, 303, 305, 322, 
			323, 325, 329, 330, 352, 
			353, 355, 500, 505, 530, 
			531, 532, 554, 600, 409, 
			601, 602, 603, 604, 621, 
			622, 623, 624, 700, 304, 
			701, 702, 721, 722, 723, 
			724, 725, 751, 754, 800, 
			301, 307, 308, 326, 327, 
			328, 356, 357, 358, 901, 
			903, 904, 906, 907];
		if(!in_array($department, $legalDepartment))
		{
			echo json_encode('Error: Department id is illegal.');
			exit;
		}

		return $this->loadModel('getCourse')->getCourseList($grade, $department, $year, $semester);
	}
}
?>