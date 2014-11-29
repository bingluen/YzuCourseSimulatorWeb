<script src="<?php echo URL; ?>public/js/application.js"></script>
<?php
$time = array("08:10~09:00", "09:10~10:00", "10:10~11:00", "11:10~12:00",
     "12:10~13:00", "13:10~14:00", "14:10~15:00", "15:10~16:00",
     "16:10~17:00", "17:10~18:00", "18:30~19:20", "19:30~20:20",
     "20:30~21:20");
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">免責聲明</h4>
      </div>
      <div class="modal-body">
        <p>本系統並<em><strong>非</strong></em>元智大學官方提供的網頁，<em><strong>不保證</strong></em>課程資訊的正確性；若本系統擷取課程資訊後課務組因故有任何異動，請以<a href="https://portal.yzu.edu.tw/vc2/global_cos.aspx" target="_blank">校方公佈的開課資訊</a>為準。</p>
        <p><em><strong>注意</strong></em>：系統內所有課程資訊並<em><strong>不會</strong></em>即時更新，正式選課前請務必重新核對課表上的內容與<a href="https://portal.yzu.edu.tw/vc2/global_cos.aspx" target="_blank">校方公佈</a>是否相符。</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">同意</button>
      </div>
    </div>
  </div>
</div>


<div class="page-header" style="text-align:center"><h2>元智大學選課模擬系統</h2></div>
<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div id="courseSearchFrom">
                <div class="col-md-9">
                    <input type="text" class="form-control" placeholder="輸入老師、課名或時間" id="keyWord">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="searchButton">搜尋</button>
                </div>
            </div>
        </div>
        <div class="row">
            <table class="table table-hover searchReslut">
                <thead>
                    <!--<th>年級</th>-->
                    <th>課號</th>
                    <th>課名</th>
                    <th>教師</th>
                    <th>時間</th>
                </thead>
                <tbody id="searchReslut">
                    <!--
                        <td><button type="button" class="btn btn-primary" onClick="add('207,208,209','EE351 A<br/>自動控制（一）<br/>黃英哲(Ying-Jeh Huang)')">加選</button></td>
                    -->
                    <tr>
                        <div class="progress progress-striped active loadingbar">
                            <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                            </div>
                        </div>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-hover">
            <thead>
                <th class="columT"></th>
                <th class="colum">Mon.</th>
                <th class="colum">Tue.</th>
                <th class="colum">Wen.</th>
                <th class="colum">Thu.</th>
                <th class="colum">Fri.</th>
                <!--<th class="colum">Sat.</th>-->
            </thead>
            <?php for($i = 1;$i < 13;$i++) {?>
            <tr>
                <th>第<?=$i;?>節<br/><?=$time[$i-1];?></th>
                <td id="1<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>
                <td id="2<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>
                <td id="3<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>
                <td id="4<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>
                <td id="5<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>
                <!--<td id="6<?=str_pad($i, 2,'0',STR_PAD_LEFT);?>"></td>-->
            </tr>
            <?php } ?>
        </table>
    </div>
</div>