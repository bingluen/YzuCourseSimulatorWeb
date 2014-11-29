var departmentList = [];
	departmentList[departmentList.length] = {id: 300, name: "工程學院"};
	departmentList[departmentList.length] = {id: 302, name: "機械工程學系學士班"};
	departmentList[departmentList.length] = {id: 303, name: "化學工程與材料科學學系學士班"};
	departmentList[departmentList.length] = {id: 305, name: "工業工程與管理學系學士班"};
	departmentList[departmentList.length] = {id: 322, name: "機械工程學系碩士班"};
	departmentList[departmentList.length] = {id: 323, name: "化學工程與材料科學學系碩士班"};
	departmentList[departmentList.length] = {id: 325, name: "工業工程與管理學系碩士班"};
	departmentList[departmentList.length] = {id: 329, name: "生物科技與工程研究所碩士班"};
	departmentList[departmentList.length] = {id: 330, name: "先進能源碩士學位學程"};
	departmentList[departmentList.length] = {id: 352, name: "機械工程學系博士班"};
	departmentList[departmentList.length] = {id: 353, name: "化學工程與材料科學學系博士班"};
	departmentList[departmentList.length] = {id: 355, name: "工業工程與管理學系博士班"};
	departmentList[departmentList.length] = {id: 500, name: "管理學院"};
	departmentList[departmentList.length] = {id: 505, name: "管理學院學士班"};
	departmentList[departmentList.length] = {id: 530, name: "管理學院經營管理碩士班"};
	departmentList[departmentList.length] = {id: 531, name: "管理學院商學碩士班"};
	departmentList[departmentList.length] = {id: 532, name: "管理學院管理碩士在職專班"};
	departmentList[departmentList.length] = {id: 554, name: "管理學院博士班"};
	departmentList[departmentList.length] = {id: 600, name: "人文社會學院"};
	departmentList[departmentList.length] = {id: 409, name: "應用外語學系學士班"};
	departmentList[departmentList.length] = {id: 601, name: "應用外語學系學士班"};
	departmentList[departmentList.length] = {id: 602, name: "中國語文學系學士班"};
	departmentList[departmentList.length] = {id: 603, name: "藝術與設計學系學士班"};
	departmentList[departmentList.length] = {id: 604, name: "社會暨政策科學學系學士班"};
	departmentList[departmentList.length] = {id: 621, name: "應用外語學系碩士班"};
	departmentList[departmentList.length] = {id: 622, name: "中國語文學系碩士班"};
	departmentList[departmentList.length] = {id: 623, name: "藝術與設計學系(藝術管理碩士班)"};
	departmentList[departmentList.length] = {id: 624, name: "社會暨政策科學學系碩士班"};
	departmentList[departmentList.length] = {id: 700, name: "資訊學院"};
	departmentList[departmentList.length] = {id: 304, name: "資訊工程學系學士班"};
	departmentList[departmentList.length] = {id: 701, name: "資訊管理學系學士班"};
	departmentList[departmentList.length] = {id: 702, name: "資訊傳播學系學士班"};
	departmentList[departmentList.length] = {id: 721, name: "資訊管理學系碩士班"};
	departmentList[departmentList.length] = {id: 722, name: "資訊傳播學系碩士班"};
	departmentList[departmentList.length] = {id: 723, name: "資訊社會學碩士學位學程"};
	departmentList[departmentList.length] = {id: 724, name: "資訊工程學系碩士班"};
	departmentList[departmentList.length] = {id: 725, name: "生物與醫學資訊碩士學位學程"};
	departmentList[departmentList.length] = {id: 751, name: "資訊管理學系博士班"};
	departmentList[departmentList.length] = {id: 754, name: "資訊工程學系博士班"};
	departmentList[departmentList.length] = {id: 800, name: "電機通訊學院"};
	departmentList[departmentList.length] = {id: 301, name: "電機工程學系學士班"};
	departmentList[departmentList.length] = {id: 307, name: "通訊工程學系學士班"};
	departmentList[departmentList.length] = {id: 308, name: "光電工程學系學士班"};
	departmentList[departmentList.length] = {id: 326, name: "電機工程學系碩士班"};
	departmentList[departmentList.length] = {id: 327, name: "通訊工程學系碩士班"};
	departmentList[departmentList.length] = {id: 328, name: "光電工程學系碩士班"};
	departmentList[departmentList.length] = {id: 356, name: "電機工程學系博士班"};
	departmentList[departmentList.length] = {id: 357, name: "通訊工程學系博士班"};
	departmentList[departmentList.length] = {id: 358, name: "光電工程學系博士班"};
	departmentList[departmentList.length] = {id: 901, name: "通識教學部"};
	departmentList[departmentList.length] = {id: 903, name: "軍訓室"};
	departmentList[departmentList.length] = {id: 904, name: "體育室"};
	departmentList[departmentList.length] = {id: 906, name: "國際語言文化中心"};
	departmentList[departmentList.length] = {id: 907, name: "國際兩岸事務室"};

var JsonDATA = '';

$(document).ready(function() {
    $('.button-group').hide();
    scrollToBottom();
    prepareDepartmentList();
});

function scrollToBottom() {
    $("#exces").each(function() {
        // certain browsers have a bug such that scrollHeight is too small
        // when content does not fill the client area of the element
        var scrollHeight = Math.max(this.scrollHeight, this.clientHeight);
        this.scrollTop = scrollHeight - this.clientHeight;
    });
}

function doGetCourse(departmentKey, year, semester) {
    $('.button-group').hide();
    $('#data').empty();
    var str = '';
    $.ajax({
            url: 'CatchCourse/API/CatchCourse/'+departmentList[departmentKey]['id']+'/0/',
            type: 'GET',
            dataType: 'JSON',
        })
        .done(function(data) {
        	console.log(data);
            str += '<div class=\'alert alert-success\'>';
            str += '<b>Success :</b> Catch ' + year + semester + ' ' + departmentList[departmentKey]['name'];
            str += '</div>';
            $('#exces').append(str);
            displayTable(data);
        })
        .fail(function() {
            str += '<div class=\'alert alert-danger\'>';
            str += '<b>Error :</b> Catch ' + year + semester + ' ' + departmentList[departmentKey]['name'];
            str += '</div>';
            $('#exces').append(str);
        })
        .always(function() {
            scrollToBottom();
        });
}

function getJSON() {
	$('#GETJSON').empty();
	$('#GETJSON').append(departmentList[$('#departmentKey option:selected').val()]['name']+'課程資料 - JSON格式')
	$('#JSONDATA').empty();
	$('#JSONDATA').append(JSON.stringify(JsonDATA));
	$('#myModal').modal('show');
}

function displayTable(data) {
    JsonDATA = data;
    dataRow = '';
    dataRow += '<thead>';
    dataRow += '<th>Code</th>';
    dataRow += '<th>Time</th>';
    dataRow += '<th>name</th>';
    dataRow += '<th>Teacher</th>';
    dataRow += '</thead>';
    dataRow += '<tbody>';
    for (var i in data) {
        dataRow += '<tr>';
        dataRow += '<td>' + data[i]['courseCODE'] + '</td>'
        dataRow += '<td>' + data[i]['courseTime'] + '</td>'
        dataRow += '<td>' + data[i]['courseCN'] + '</td>'
        dataRow += '<td>' + data[i]['courseTeacher'] + '</td>'
        dataRow += '</tr>';
        if (data[i]['courseCODE'] === undefined || data[i]['courseTime'] === undefined || data[i]['courseCN'] === undefined || data[i]['courseTeacher'] === undefined) {
            throwWarning(data[i]);
        }
    }
    dataRow += '</tbody>';
    $('#data').append(dataRow);
    $('.button-group').show();
}

function throwWarning(data) {
    str = ''
    str += '<div class=\'alert alert-warning\'>';
    str += '<b>warning - Data Undefine :</b>' + data['courseCODE'] + data['courseTime'] + data['courseCN'] + data['courseTeacher'];
    str += '</div>';
    $('#exces').append(str)
    scrollToBottom();
}

function skip() {
    $('#departmentKey').val(parseInt($('#departmentKey option:selected').val()) + 1);
    doGetCourse($('#departmentKey option:selected').val(), $('#year').val(), $('#semester').val());
}

function prepareDepartmentList()
{
	var list = $.map(departmentList, function(department, index){
		return '<option value="'+index+'">'+department['name']+'</option>'
	});

	for (var i = 0; i < list.length; i++) {
		$('#departmentKey').append(list[i]);
		$('#APIdepartmentKey').append(list[i]);
	};
}

function renewAPI()
{
	$('#CatchCourseAPI').attr("value", $('#CatchCourseAPI').attr('APIbase')+'CatchCourse/API/CatchCourse/'+departmentList[$('#APIdepartmentKey option:selected').val()]['id']+'/'+$('#APIgrade option:selected').val()+'/');
}