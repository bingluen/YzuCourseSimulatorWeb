<?php
class getHomework
{
	private $count;
	private $homeworkList;
	private $courseList;
	private $homeworkPages;

	private function loginPortal($username, $password)
	{
		$content = '';

	    $url = 'https://portalx.yzu.edu.tw/PortalSocialVB/Login.aspx';
	    $connect = curl_init();
	    $option = array(
	        CURLOPT_URL => $url,
	        CURLOPT_HEADER => 0,
	        CURLOPT_REFERER => $url,
	        CURLOPT_COOKIEJAR => 'cookie.txt',
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false
	        );
	    curl_setopt_array($connect, $option);

	    $content = curl_exec($connect);
	    curl_close($connect);

	    // catch environment parameters

	        //here is using regular expression
	        preg_match_all('/__VIEWSTATE" value="(.*)"/' , $content , $res );
	        $VIEWSTATE = $res[1][0];

	        //here is using regular expression
	        preg_match_all('/__EVENTVALIDATION" value="(.*)" /', $content , $res);
	        $EVENTVALIDATION = $res[1][0];

	        //here is using regular expression
	        preg_match_all('/__VIEWSTATEGENERATOR" value="(.*)"/' , $content , $res );
	        $VIEWSTATEGENERATOR = $res[1][0];

	        $data = array('__VIEWSTATE' => $VIEWSTATE,
	            '__VIEWSTATEGENERATOR' => $VIEWSTATEGENERATOR,
	            '__EVENTVALIDATION' => $EVENTVALIDATION,
	            'Txt_UserID' => $username,
	            'Txt_Password' => $password,
	            'ibnSubmit' => '登入',
	            );

	    //prepare second connect for login
	    $connect = curl_init();
	    $option = array(
	        CURLOPT_URL => $url,
	        CURLOPT_HEADER => 0,
	        CURLOPT_REFERER => $url,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_FOLLOWLOCATION => false,
	        CURLOPT_COOKIEFILE => 'cookie.txt',
	        CURLOPT_COOKIEJAR => 'cookie.txt',
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false,
	        CURLOPT_POST => true,
	        CURLOPT_POSTFIELDS => $data
	        //CURLOPT_POSTFIELDS => 'sid='.session_id()
	        );

	    curl_setopt_array($connect, $option);
	    $content = curl_exec($connect);
	    curl_close($connect);

	    //轉入main
	    $connect = curl_init();
	    $option = array(
	        CURLOPT_URL => 'https://portalx.yzu.edu.tw/PortalSocialVB/FMain/DefaultPage.aspx',
	        CURLOPT_HEADER => 0,
	        CURLOPT_REFERER => $url,
	        CURLOPT_RETURNTRANSFER => 1,
	        CURLOPT_FOLLOWLOCATION => false,
	        CURLOPT_COOKIEFILE => 'cookie.txt',
	        CURLOPT_COOKIEJAR => 'cookie.txt',
	        CURLOPT_SSL_VERIFYPEER => false,
	        CURLOPT_SSL_VERIFYHOST => false
	        );

	    curl_setopt_array($connect, $option);
	    $content = curl_exec($connect);
	    curl_close($connect);
	    return $content;
	}

	private function parseCourseList($data)
	{
		$this->courseList = '';
	    $homeworkDOM = new DOMDocument;
	    //@ 是忽略錯誤
	    @$homeworkDOM->loadHTML($data);
	    //mypage 學生通常應該是上課的...
	    $mypage = $homeworkDOM->getElementById('MainLeftMenu_divMyPage');
	    foreach ($mypage->childNodes as $subject) {
	        $matchData = '';
	        preg_match_all('/GoToPage\(\'([0-9]*)\',\'.*\'\);/', $subject->childNodes->item(0)->childNodes->item(1)->getAttribute('onclick'), $matchData);
	        $this->courseList[] = $matchData[1][0];
	    }
	}

	private function parseHomeworkList()
	{
		$this->count = 0;
		$this->homeworkList = '';
	    /*
	    *   用 HTML DOM Parse...Q__Q
	    */
	    foreach ($this->homeworkPages as $homeworkPage) {
	    	$homeworkDOM = new DOMDocument;
		    //@ 是忽略錯誤
		    @$homeworkDOM->loadHTML($homeworkPage);
		    //作業的Table  id = Table1;
		    $table = $homeworkDOM->getElementById('Table1');
		    $trs = $table->getElementsByTagName('tr');

		    /*
		        取得作業清單，2個ROW是一筆資料（幹，這網頁設計0分）
		    */
		    $homeworkRows = '';
		    foreach ($trs as $tr)
		    {
		        if($tr->hasAttribute('class') 
		            && $tr->getAttribute('class') != 'title_line')
		            $homeworkRows[] = $tr;
		    }
		    if(!$homeworkRows)
		    	continue;
		    /*
		        把資料合併起來，兩列為一個作業
		    */
		    for ($i = 0; $i < sizeof($homeworkRows) ; $i++)
		    {
		        if($i%2)
		        {
		            /* 偶數列 長度 = 2 只有第一個值(index = 0)有用(作業說明)*/
		            $this->count++;
		        } else {
		            /* 

		                奇數列 長度 = 12 
		                0 = portal給的編號 （基本上無用）
		                1 = 進度欄位
		                2 = 作業名稱
		                3 = 題目檔案欄位 (沒上傳的話會是長度0的string)
		                4 = deadline
		                5 = 學生上傳的東西
		                6 = 可執行動作 （基本上也是無用，上傳檔案的連結）
		                7 = 屬性 分為個人/小組？
		                8 = 自由繳交 只有 N 和 Y
		                9 = 成績
		                10 = 評語
		                11 = 沒用的垃圾欄位，學校太廢物code寫太髒

		            */
		            $this->homeworkList[$this->count]['schedule'] = $homeworkRows[$i]->childNodes->item(1)->nodeValue;
		            $this->homeworkList[$this->count]['subject'] = $homeworkRows[$i]->childNodes->item(2)->nodeValue;
		            $this->homeworkList[$this->count]['deadline'] = $homeworkRows[$i]->childNodes->item(4)->nodeValue;
		            $this->homeworkList[$this->count]['type'] = $homeworkRows[$i]->childNodes->item(7)->nodeValue;
		            $this->homeworkList[$this->count]['free'] = ($homeworkRows[$i]->childNodes->item(8)->nodeValue == 'N' ? false : true);
		            $this->homeworkList[$this->count]['grade'] = $homeworkRows[$i]->childNodes->item(9)->nodeValue;
		            $this->homeworkList[$this->count]['comment'] = $homeworkRows[$i]->childNodes->item(10)->nodeValue;


		            /*
		                處理一些比較靠杯麻煩的 （題目檔案欄位 & 學生上傳的東西）
		            */
		                //拿題目檔案
		                $attachementParameter = '';
		                if($homeworkRows[$i]->childNodes->item(3)->childNodes->item(0))
		                {
		                    //親愛的BOSS大大有上傳題目附件的時候
		                    $attachementParameter = preg_split('/[=&?]/',
		                        $homeworkRows[$i]->childNodes->item(3)->childNodes->item(0)->getAttribute('href'));
		                    $this->homeworkList[$this->count]['attache'][$attachementParameter[1]] = $attachementParameter[2];
		                    $this->homeworkList[$this->count]['attache'][$attachementParameter[3]] = $attachementParameter[4];
		                    $this->homeworkList[$this->count]['attache'][$attachementParameter[5]] = $attachementParameter[6];
		                }
		                else
		                {
		                    //沒上傳的時候=w=
		                    $this->homeworkList[$this->count]['attachement'] = false;
		                }
		                /*
		                    拿學生上傳的檔案
		                    靠杯麻煩~ 可能會上傳N個= =
		                    暫時回傳是否上傳就好，只要有一個沒作廢（合法上傳）就視為已上傳
		                */
		                $this->homeworkList[$this->count]['upload'] = false;
		                foreach ($homeworkRows[$i]->childNodes->item(5)->getElementsByTagName('a') as $childNode) {
		                    if(!preg_match('/(作廢)/', $childNode->nodeValue))
		                        $this->homeworkList[$this->count]['upload'] = true;
		                }   
		        }
		    }
	    }
		    
	    if($this->count > 0)
	    	return true;
	    else
	    	return false;
	}

	private function getHomeworkPage()
	{
		$this->homeworkPages = '';
		foreach ($this->courseList as $courseId) {
			$content = '';
			//尋訪各專頁面
		    $connect = curl_init();
		    $option = array(
		        CURLOPT_URL => 'https://portalx.yzu.edu.tw/PortalSocialVB/FPage/FirstToPage.aspx?PageID='.$courseId,
		        CURLOPT_HEADER => 0,
		        CURLOPT_REFERER => 'https://portalx.yzu.edu.tw/PortalSocialVB/FMain/DefaultPage.aspx',
		        CURLOPT_RETURNTRANSFER => 1,
		        CURLOPT_FOLLOWLOCATION => true,
		        CURLOPT_COOKIEFILE => 'cookie.txt',
		        CURLOPT_COOKIEJAR => 'cookie.txt',
		        CURLOPT_SSL_VERIFYPEER => false,
		        CURLOPT_SSL_VERIFYHOST => false
		        );

		    curl_setopt_array($connect, $option);
		    $content = curl_exec($connect);
		    curl_close($connect);

		    //拉作業=Q=
		    $connect = curl_init();
		    $option = array(
		        CURLOPT_URL => 'https://portalx.yzu.edu.tw/PortalSocialVB/FMain/ClickMenuLog.aspx?type=Pag_Homework_S',
		        CURLOPT_HEADER => 0,
		        CURLOPT_REFERER => 'https://portalx.yzu.edu.tw/PortalSocialVB/FPage/FirstToPage.aspx?PageID='.$courseId,
		        CURLOPT_RETURNTRANSFER => 1,
		        CURLOPT_FOLLOWLOCATION => true,
		        CURLOPT_COOKIEFILE => 'cookie.txt',
		        CURLOPT_COOKIEJAR => 'cookie.txt',
		        CURLOPT_SSL_VERIFYPEER => false,
		        CURLOPT_SSL_VERIFYHOST => false
		        );

		    curl_setopt_array($connect, $option);
		    $content = curl_exec($connect);
		    curl_close($connect);

		    $this->homeworkPages[] = $content;
		}
	}

	public function executeCatch($userData)
	{
		/*
			理論上執行步驟
			1.先loginPortal
			2.把登入的課程表parse出來
				（如果prase結果為空，則登入失敗）
			3.尋訪每個科目專頁
			4.把每個科目的作業parse出來
		*/

		$this->parseCourseList($this->loginPortal($userData['user'], $userData['pass']));
		$this->getHomeworkPage();
		if($this->parseHomeworkList())
			return true;
	}


	public function getHomeworkList()
	{
		return $this->homeworkList;
	}

	public function getCourseList()
	{
		return $this->courseList;
	}

	public function getHomeworkNum()
	{
		return $this->count;
	}
}
?>