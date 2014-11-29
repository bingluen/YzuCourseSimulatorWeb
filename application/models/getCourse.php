<?php
class getCourse
{
	private function catchCourseData($degree, $department, $year, $semester)
	{
	    $catchYear = $year.','.$semester.'  ';
	    //setp 1  prepare first connect
	    $content = '';
	    $url = 'https://portal.yzu.edu.tw/cosSelect/Index.aspx';
	    $connect = curl_init();
	    $option = array(
	        CURLOPT_URL => $url,
	        CURLOPT_HEADER => 0,
	        CURLOPT_REFERER => $url,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_COOKIEJAR => 'cookie.txt',
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false
	        );
	    curl_setopt_array($connect, $option);

	    //step 2 first connect
	    $content = curl_exec($connect);
	    curl_close($connect);

	    // catch environment parameters

	        //here is using regular expression
	        preg_match_all('/__VIEWSTATE" value="(.*)"/' , $content , $res );
	        $VIEWSTATE = $res[1][0];

	        //here is using regular expression
	        preg_match_all('/__EVENTVALIDATION" value="(.*)" /', $content , $res);
	        $EVENTVALIDATION = $res[1][0];

	        $data = array('__VIEWSTATE' => $VIEWSTATE,
	            '__EVENTVALIDATION' => $EVENTVALIDATION,
	            'DDL_YM' => $catchYear,
	            'DDL_Dept' => $department,
	            'DDL_Degree' => '1',
	            'Q' => 'RadioButton1',
	            '__EVENTTARGET'=> '',
	            '__EVENTARGUMENT'=> '',
	            '__LASTFOCUS'=> '',
	            'agree'=>'',
	            'Button1'=>'確定'
	            );

	    //setp 3  prepare second connect for choice department
	        $connect = curl_init();
	        $option = array(
	            CURLOPT_URL => $url,
	            CURLOPT_HEADER => 0,
	            CURLOPT_REFERER => $url,
	            CURLOPT_FOLLOWLOCATION => true,
	            CURLOPT_COOKIEFILE => 'cookie.txt',
	            CURLOPT_COOKIEJAR => 'cookie.txt',
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_SSL_VERIFYPEER => false,
	            CURLOPT_SSL_VERIFYHOST => false,
	            CURLOPT_POST => true,
	            CURLOPT_POSTFIELDS => $data
	            );

	    //setp 4 second connect 
	        curl_setopt_array($connect, $option);
	        $content = curl_exec($connect);
	        curl_close($connect);

	    //  re-catch environment parameters
	        preg_match_all('/__VIEWSTATE" value="(.*)"/' , $content , $res );
	        $VIEWSTATE = $res[1][0];
	        preg_match_all('/__EVENTVALIDATION" value="(.*)" /', $content , $res);
	        $EVENTVALIDATION = $res[1][0];

	        $data = array('__VIEWSTATE' => $VIEWSTATE,
	            '__EVENTVALIDATION' => $EVENTVALIDATION,
	            'DDL_YM' => $catchYear,
	            'DDL_Dept' => $department,
	            'DDL_Degree' => $degree,
	            'Q' => 'RadioButton1',
	            '__EVENTTARGET'=> '',
	            '__EVENTARGUMENT'=> '',
	            '__LASTFOCUS'=> '',
	            'agree'=>'',
	            'Button1'=>'確定'
	            );

	    //step 5 prepare third connect for choice year
	        $connect = curl_init();
	        $option = array(
	            CURLOPT_URL => $url,
	            CURLOPT_HEADER => 0,
	            CURLOPT_REFERER => $url,
	            CURLOPT_FOLLOWLOCATION => true,
	            CURLOPT_COOKIEFILE => 'cookie.txt',
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_SSL_VERIFYPEER => false,
	            CURLOPT_SSL_VERIFYHOST => false,
	            CURLOPT_POST => true,
	            CURLOPT_POSTFIELDS => $data
	            );
	    // step 6 third connect
	        curl_setopt_array($connect, $option);
	        $content = curl_exec($connect);
	        curl_close($connect);

	    // return raw data
	    return $content;
	}

	private function parseData($data)
	{
	    $result = '';
	    $parse = '';
	    $parseResult = '';

	    //get Course chinese name
	    preg_match_all('/Cos_Plan.aspx?.* target="_blank">(.*)<\/a><a href="/', $data, $parse);
	    $result['courseCN'] = $parse[1];

	    //get Course Code
	    preg_match_all('/" target="_blank">([A-Z]{2}[0-9]{3} [A-Z][0-9]?)<\/a><\/td><td align="left">/', $data, $parse);
	    $result['courseCODE'] = $parse[1];

	    //get Course Time
	    preg_match_all('/<span>(([0-9]{3}([ ]+<br>[0-9]{3})*)?)<\/span>/', $data, $parse);
	        //去掉br
	        $parse[1] = preg_replace('/[ ]+<br>/', ', ', $parse[1]);
	    $result['courseTime'] = $parse[1];

	    //get Teacher
	    preg_match_all('/<td align="left">(([\x{4E00}-\x{9FFF}A-Za-z ]+[\n]*(\([-A-Za-z .,\x{4E00}-\x{9FFF}\x{ff0c}]+\))?)(、[\x{4E00}-\x{9FFF}A-Za-z ]+(\([-A-Za-z .,\x{4E00}-\x{9FFF}\x{ff0c}]+\))?)*)<\/td>/u', $data, $parse);
	    $result['courseTeacher'] = $parse[1];

	    //convert data ?
	    foreach ($result['courseCN'] as $key => $value) {
	        $parseResult[$key]['courseCN'] = $value;
	    }
	    foreach ($result['courseCODE'] as $key => $value) {
	        $parseResult[$key]['courseCODE'] = $value;
	    }
	    foreach ($result['courseTime'] as $key => $value) {
	        $parseResult[$key]['courseTime'] = $value;
	    }
	    foreach ($result['courseTeacher'] as $key => $value) {
	        $parseResult[$key]['courseTeacher'] = $value;
	    }

	    return $parseResult;
	}

	public function getCourseList($degree, $department, $year, $semester)
	{
		return $this->parseData($this->catchCourseData($degree, $department, $year, $semester));
	}
}
?>