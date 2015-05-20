function add(time, course) {
    var position = time.split(',');
    for(var i = 0;i < position.length; i++) {
        var content = '<div id="button'+position[i]+'" class="coursebutton"><a href="#" class="glyphicon glyphicon-remove" onClick="removeCourse(\u0027'+time+'\u0027)"></a></div><div>'+course+'</div>';
        $('#'+position[i]).html(content);
        //$('#'+position[i]).attr('onClick','removeCourse(\u0027'+time+'\u0027)');
        //$('#'+position[i]).attr('onhover', 'onhoverCourse(\u0027'+time+'\0027)');
    }
}

function removeCourse(time) {
    var position = time.split(', ');
    for(var i = 0;i < position.length;i++) {
        $('#'+position[i]).html('');
        $('#'+position[i]).removeAttr('onClick');
    }
}

//查詢功能
$(document).ready(
    function() {
        $('#myModal').modal('show');
        $('#searchButton').click(
            function() {
                $('#animationProcess').show();
                $('#resultTable').hide();
                $('#searchReslut tr').remove();
                $.ajax({
                    url: '/CourseSelection/Search/',
                    dataType: 'json',
                    type: 'post',
                    data: {key: $('#keyWord').val()},
                    success: function(response) {
                        $('#animationProcess').hide();
                        $('#resultTable').show();
                        displayResult(response);
                    },
                    error: function(xth) {
                        alert('Query fails!!');
                    }
                });
            });
    });

function displayResult(data) {
    for(var i = 0;i < data.length;i++) {
        var str = '';
        str += '<tr>'
        str += '<td>'+data[i]['code']+'</td>';
        str += '<td>'+data[i]['cname']+'</td>';
        str += '<td>'+data[i]['professor']+'</td>';
        str += '<td class=\x27coursebutton-inverse\x27>'+data[i]['time']+'</td>';
        str += "<td class='coursebutton'><button type=\x22button\x22 class=\x22btn btn-primary\x22 onClick=\x22add(\x27"+data[i]['time']+"\x27,\x27"+data[i]['code']+"<br>"+data[i]['cname']+"<br>"+data[i]['professor']+"\x27)\x22>加選</button></td>";
        str += '</tr>';
        $('#searchReslut').append(str);
    }
}
