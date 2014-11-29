<script src="<?=URL?>public/js/catchCourse.js"></script>
<div class="container">
    <h1>
        元智大學課程資訊擷取 
        <button type="button" class="btn btn-default btn-lg" onclick="$('#api').slideToggle(300);">
            <span class="glyphicon glyphicon-cog"></span>API
        </button>
    </h1>
    <div id="api" class="jumbotron" style="display: none;">
        <div class="container" style="padding-bottom: 50px;">
            <h3>開放API</h3>
            <p>目前為beta版，僅開放1032學期的課程擷取。API會以JSON傳回如下方表格之資料</p>
            <div class="form-group">
                <label for="departmentKey">API位置</label>
                <input id="CatchCourseAPI" type="text" class="form-control" APIbase="<?=URL?>" value="">
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <label for="departmentKey">系所</label>
                    <select id="APIdepartmentKey" class="form-control" onchange="renewAPI();"> 
                    </select> 
                    <label for="departmentKey">年級</label>
                    <select id="APIgrade" class="form-control" onchange="renewAPI();">
                        <option value="0">ALL</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select> 
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body form-inline" style="font-size: 15px; text-align: center">
                Search for
                <div class="form-group">
                    <label for="year">Year </label>
                    <input style="width:80px !important;" type="text" class="form-control" id="year" value="103" disabled>
                </div>
                <div class="form-group">
                    <label for="semester">Semester </label>
                    <input style="width:40px !important;" type="text" class="form-control" id="semester" value="2" disabled>
                </div>
                <label>And Total of Department are <span class="label label-info" style="font-size:15px;"><script type="text/javascript">document.write(departmentList.length);</script></span></label>
                <div class="form-group">
                    <label for="departmentKey">Now in </label>
                    <!--<input style="width:50px !important;" type="text" class="form-control" id="departmentKey" value="1">-->
                    <select id="departmentKey" class="form-control"> 
                    </select> 
                </div>
                <button class="btn btn-primary " onclick="doGetCourse($('#departmentKey option:selected').val(), $('#year').val(), $('#semester').val());">Catch it!</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="jumbotron">
            <div class="container">
                <h2>Logs<button type="button" class="btn btn-link" onclick="$('#exces').empty();">Clear</button></h2>
                <div id="exces" style="height: 500px; overflow: auto;">
                </div>
            </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="button-group" style="text-align: right;">
                <button class="btn btn-primary btn-lg" onclick="getJSON();">JSON</button>
                <button class="btn btn-info btn-lg" onclick="doGetCourse($('#departmentKey option:selected').val(), $('#year').val(), $('#semester').val());">Refresh</button>
                <button class="btn btn-default btn-lg" onclick="skip();">Skip</button>
            </div>
            <table id="data" class="table">
            </table>
            <div class="button-group" style="text-align: right;">
                <button class="btn btn-primary btn-lg" onclick="getJSON();">JSON</button>
                <button class="btn btn-info btn-lg" onclick="doGetCourse($('#departmentKey option:selected').val(), $('#year').val(), $('#semester').val());">Refresh</button>
                <button class="btn btn-default btn-lg" onclick="skip();">Skip</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="GETJSON"></h4>
      </div>
      <div class="modal-body">
        <textarea id="JSONDATA" class="form-control" row="10"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>