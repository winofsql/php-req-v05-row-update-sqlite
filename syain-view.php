<!DOCTYPE html>
<html>

<head>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta charset="utf-8">
<title><?= $title ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.js"></script>

<style>
html,body,form {
    height: 100%;
}
#head {
    display: block;
    margin: auto;
    width: 100%;
    height: 160px!important;
    height: 100%;
}
#content {
    padding: 4px 16px;
    display: block;
    margin: auto;
    width: calc( 100% - 3px );
    height: calc( 100% - 160px - 2px );
    border: solid 2px #c0c0c0;
    overflow: scroll;
}

td,th {
    cursor: default!important;
}
th {
    white-space: pre;
}

#tbl {
    user-select: none;
}

.w100 {
    width: 100px;
}

.pre {
    white-space: pre;
}

.folder {
    float: right;
}
</style>
<script>
$(function(){

    $("form").on("submit", function(){

        if ( !confirm("更新してもよろしいですか?") ) {
            event.preventDefault();
            return;
        }

    });

    $("#action_save").on( "click", function(){

        // CSV テキスト用変数
        var csv = "";

        // データの行数
        var cnt = 0;
        $("table")
            // 行単位で処理
            .find("tr").each( function(){

            // TH の最初の一行は処理しない )
            if ( cnt > 0 ) {

                // 行内の TD を全て処理
                $(this).find("td").each(function( col_cnt ){
                    // 先頭列以外はカンマを付加
                    if ( col_cnt != 0 ) {
                        csv += ",";
                    }

                    if ( col_cnt == 0 ) {
                    // Excel で文字列をそのまま取り込めるように( 例. 0001 を文字列として扱う )
                        csv += "=\"" + $(this).text() + "\"";
                    }
                    else {
                        if ( col_cnt == 5 ) {
                            csv += "\"" + $(this).find("input[type=text]").val() + "\"";
                        }
                        else {
                            csv += "\"" + $(this).text() + "\"";
                        }
                    }
                });
                // 行の最後に改行
                csv += "\n";
            }

            // 行数のカウント
            cnt++;

        });

        // UTF-8 の CSV を化けずに Excel で開く為
        saveAs(
            new Blob(
                [new Uint8Array([0xEF, 0xBB, 0xBF]),csv]
                , {type: "text/csv;charset=" + document.characterSet}
            )
            , "syain.csv"
        );

    });

});
</Script>
</head>

<body>
<form method="post">
    <div id="head">
        <h3 class="alert alert-primary">
            <?= $title ?>
            <input id="action_save" type="button" value="CSV保存" class="btn btn-primary ms-3">
            <a href="." class="btn btn-secondary btn-sm folder me-4">フォルダ</a>
        </h3>
        <input type="submit" name="update" value="更新" class="ms-4 btn btn-primary">
    </div>
    <div id="content">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="w100">社員コード</th>
                            <th>氏名</th>
                            <th>フリガナ</th>
                            <th>所属</th>
                            <th>性別</th>
                            <th>給与</th>
                            <th class="text-end">手当</th>
                            <th>管理者</th>
                            <th>生年月日</th>
                        </tr>
                    </thead>
                    <tbody id="tbl">
                        <?= $html ?>
                    </tbody>
                </table>
            </div>
    </div>
</form>
</body>
</html>