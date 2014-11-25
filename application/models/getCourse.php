<?php
    require_once('config.php');
    //parseData(catchCourseData(0, 304));

    if(isset($_GET['action']) 
        && $_GET['action'] === 'getCourse' 
        && isset($_POST['department']) 
        && isset($_POST['year']) 
        && isset($_POST['semester'])) {
        echo json_encode(parseData(
            catchCourseData(0, 
                $_POST['department'], 
                $_POST['year'],
                $_POST['semester'])));
        exit;
    }

    if(isset($_GET['action']) 
        && $_GET['action'] === 'insert' 
        && isset($_POST['data']) 
        && isset($_POST['year']) 
        && isset($_POST['semester'])) {
        insertData($_POST['data'], $_POST['year'], $_POST['semester']);
        echo json_encode('success');
        exit;
    }

function catchCourseData($degree, $department, $year, $semester) {
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

function parseData($data)
{
    $result = '';
    $parse = '';

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

    /*$result = array_flip($result);
    echo "<pre>";
    echo var_dump($result);
    echo "</pre>";*/

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

function insertData($data, $year, $semester)
{
    $dsn = DatabaseType .':host=' . Host . ';dbname=' . DatabaseName;
    $dbh = new PDO ($dsn, DatabaseUser, DatabasePassword, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

    $sql = 'INSERT INTO `coursedatabase` (code, cname, year, professor, time) VALUES(?, ?, ?, ?, ?);';
    $sth = $dbh->prepare($sql);
    foreach ($data as $row) {
        try {
            $sth->execute(array($row['courseCODE'], 
            $row['courseCN'], 
            $year.$semester,
            $row['courseTeacher'],
            $row['courseTime']));
        } catch (Exception $e) {
            echo "Error in";
            print_r($row);
            exit;
        }
    }
}

?>

<html>
<head>
    <meta charset="UTF-8">
    <script type="text/javascript" src="js/jquery-2.1.0.min-local.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootflat.min.css">
    <script src="js/department.js"></script>
    <script type="text/javascript">
        var insertData = '';
        $(document).ready(function() {
            $('.button-group').hide();
            scrollToBottom();
        });
        function scrollToBottom()
        {
            $("#exces").each( function() 
            {
               // certain browsers have a bug such that scrollHeight is too small
               // when content does not fill the client area of the element
               var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
               this.scrollTop = scrollHeight - this.clientHeight;
            });
        }
        function doGetCourse(departmentKey, year, semester)
        {
            $('.button-group').hide();
            $('#data').empty();
            var str = '';
            $.ajax({
                    url: 'getCourse.php?action=getCourse',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {department: departmentList[departmentKey]['id'],
                            year: year,
                            semester: semester},
                })
                .done(function( data ) {
                    str += '<div class=\'alert alert-success\'>';
                    str += '<b>Success :</b> Catch '+year+semester+' '+departmentList[departmentKey]['name'];
                    str += '</div>';
                    $('#exces').append(str);
                    displayTable( data );
                })
                .fail(function() {
                    str += '<div class=\'alert alert-danger\'>';
                    str += '<b>Error :</b> Catch '+year+semester+' '+departmentList[departmentKey]['name'];
                    str += '</div>';
                    $('#exces').append(str);
                })
                .always(function() {
                    scrollToBottom();
                });
        }

        function doInsert()
        {
            str = '';
            $.ajax({
                    url: 'getCourse.php?action=insert',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {data: insertData,
                            year: $('#year').val(),
                            semester: $('#semester').val()},
                })
                .done(function() {
                    str += '<div class=\'alert alert-success\'>';
                    str += '<b>Success :</b> Insert '+departmentList[$('#departmentKey').val()]['name'];
                    str += '</div>';
                    $('#exces').append(str);
                    skip();
                })
                .fail(function() {
                    str += '<div class=\'alert alert-danger\'>';
                    str += '<b>Error :</b> Insert '+departmentList[$('#departmentKey').val()]['name'];
                    str += '</div>';
                    $('#exces').append(str);
                })
                .always(function() {
                    scrollToBottom();
                });
        }

        function displayTable(data) 
        {
            insertData = data;
            dataRow = '';
            dataRow += '<thead>';
            dataRow += '<th>Code</th>';
            dataRow += '<th>Time</th>';
            dataRow += '<th>name</th>';
            dataRow += '<th>Teacher</th>';
            dataRow += '</thead>';
            dataRow += '<tbody>';
            for(var i in data) 
            {
                dataRow += '<tr>';
                dataRow += '<td>'+data[i]['courseCODE']+'</td>'
                dataRow += '<td>'+data[i]['courseTime']+'</td>'
                dataRow += '<td>'+data[i]['courseCN']+'</td>'
                dataRow += '<td>'+data[i]['courseTeacher']+'</td>'
                dataRow += '</tr>';
                if(data[i]['courseCODE']  === undefined
                    || data[i]['courseTime'] === undefined
                    || data[i]['courseCN'] === undefined
                    || data[i]['courseTeacher'] === undefined)
                {
                    throwWarning(data[i]);
                }
            }
            dataRow += '</tbody>';
            $('#data').append(dataRow);
            $('.button-group').show();
        }

        function throwWarning(data)
        {
            str = ''
            str += '<div class=\'alert alert-warning\'>';
            str += '<b>warning - Data Undefine :</b>'+data['courseCODE']+data['courseTime']+data['courseCN']+data['courseTeacher'];
            str += '</div>';
            $('#exces').append(str)
            scrollToBottom();
        }
        function skip()
        {
            $('#departmentKey').val(parseInt($('#departmentKey').val())+1);
            doGetCourse($('#departmentKey').val(), $('#year').val(), $('#semester').val());
        }
    </script>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <H1>元智大學課程資訊擷取</H1>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body form-inline" style="font-size: 15px; text-align: center">
                    Search for
                    <div class="form-group">
                        <label for="year">Year </label>
                        <input style="width:80px !important;" type="text" class="form-control" id="year" value="103">
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester </label>
                        <input style="width:40px !important;" type="text" class="form-control" id="semester" value="2">
                    </div>
                    <label>And Total of Department are <span class="label label-info" style="font-size:15px;"><script type="text/javascript">document.write(departmentList.length);</script></span></label>
                    <div class="form-group">
                        <label for="departmentKey">Now in </label>
                        <input style="width:50px !important;" type="text" class="form-control" id="departmentKey" value="1">
                    </div>
                    <button class="btn btn-primary " onclick="doGetCourse($('#departmentKey').val(), $('#year').val(), $('#semester').val());">Catch it!</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="jumbotron">
                    <div class="container">
                        <h2>Logs<button type="button" class="btn btn-link" onclick="$('#exces').empty();">Clear</button></h2>
                        <div id="exces" style="height: 50%; overflow: auto;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="button-group" style="text-align: right;">
                    <button class="btn btn-primary btn-lg" onclick="doInsert();">Insert</button>
                    <button class="btn btn-info btn-lg" onclick="doGetCourse($('#departmentKey').val(), $('#year').val(), $('#semester').val());">Refresh</button>
                    <button class="btn btn-default btn-lg" onclick="skip();">Skip</button>
                </div>
                <table id="data" class="table">
                </table>
                <div class="button-group" style="text-align: right;">
                    <button class="btn btn-primary btn-lg" onclick="doInsert();">Insert</button>
                    <button class="btn btn-info btn-lg" onclick="doGetCourse($('#departmentKey').val(), $('#year').val(), $('#semester').val());">Refresh</button>
                    <button class="btn btn-default btn-lg" onclick="skip();">Skip</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
