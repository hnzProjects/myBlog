<?php
require_once('../config/connect.php');
$sql = "select * from article order by dateline desc";//dateline字段的降序排列
$query  = mysqli_query($con,$sql);
if($query&&mysqli_num_rows($query)){
    while($rows =mysqli_fetch_assoc($query)){
        $data[] = $rows;
    }
}else{
    $data = array();
}



//获取数据库的数据综述
$total="select count(*) from article";
$total_num=mysqli_fetch_array(mysqli_query($con,$total));
$totalOne=$total_num[0];
$showPage=3;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <link rel="icon" href="../images/umbrella.jpg" mce_href="../images/umbrella.jpg" type="image/x-icon" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="../css/default.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</head>

<body>
<div class="manage-header-common">
    <?php require_once('header.php');?>
</div>
<div class="container-fluid manage-outer-wrapper">
    <div class="row">
        <div class="manage-ctrl pull-left col-sm-2" align="center">
            <p><a class="nav-light"href="article.add.php">发布文章</a></p>
            <p><a class="nav-light"href="article.manage.php?p=1">管理文章</a></p>
            <p><a class="nav-light"href="end.login.php?action=loginout">退出登录</a></p>

        </div>
        <div class="pull-right col-sm-10">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover">
                    <caption>文章管理列表</caption>

                        <tr>
                            <th width="37">编号</th>
                            <th width="572">标题</th>
                            <th width="82">操作</th>
                        </tr>
                                <?php

                                $page=$_GET['p'];
                                $limitsql="select * from article order by dateline desc LIMIT " .($page-1)*8 .",8";
                                $result=mysqli_query($con,$limitsql);
                                $pageSize=10;

                                while($row=mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td>&nbsp;<?php echo $row['id']?></td>
                                            <td>&nbsp;<?php echo $row['title']?></td>
                                            <td>
                                                <a href="article.del.handle.php?id=<?php echo $row['id']?>">删除</a>
                                                <a href="article.modify.php?id=<?php echo $row['id']?>">修改</a>
                                            </td>
                                        </tr>
                                        <?php
                                }
                                mysqli_free_result($result);
                                ?>

                        <tr >
                            <td colspan="3">
                    <?php


                    //获取数据库的数据综述
                    $total="select count(*) from article";
                    $total_num=mysqli_fetch_array(mysqli_query($con,$total));
                    $totalOne=$total_num[0];
                    $showPage=3;
                    //计算页数
                    $total_pages=ceil($totalOne/8);
                    mysqli_close($con);

                    $page_banner="";

                    //偏移量
                    $pageoffset=($showPage-1)/2;

                    if($page>1)
                    {
                        $page_banner.="<a href='".$_SERVER["PHP_SELF"]. "?p=1'>首页</a>";
                        $page_banner.="<a href='".$_SERVER["PHP_SELF"]. "?p=" .($page-1). "'> < 上一页 </a>";
                    }

                    //初始化
                    $start=1;
                    $end=$total_pages;
                    if($total_pages>$showPage){
                        if($page>$pageoffset+1){
                            $page_banner.="...";
                        }
                        if($page>$pageoffset){
                            $start=$page-$pageoffset;
                            $end=$total_pages>$page+$pageoffset?$page+$pageoffset:$total_pages;
                        }else{
                            $start=1;
                            $end=$total_pages>$showPage?$showPage:$total_pages;
                        }
                        if($page+$pageoffset>$total_pages){
                            $start=$start-($page+$pageoffset-$end);
                        }
                    }

                    for($i=$start;$i<=$end;$i++){
                        $page_banner.="<div class='squar-num'>
                             <a href='".$_SERVER["PHP_SELF"]. "?p=" .($i). "'>$i</a>
                             </div>";
                    }

                    //尾部省略
                    if($total_pages>$showPage&&$total_pages>$page+$pageoffset){
                        $page_banner.="...";
                    }
                    if($page<$total_pages){
                        $page_banner.="<a href='".$_SERVER["PHP_SELF"]. "?p=" .($page+1). "'> 下一页 > </a>";
                        $page_banner.="<a href='".$_SERVER["PHP_SELF"]. "?p=" .($total_pages). "'> 尾页 </a>";

                    }
                    $page_banner.=" 共 {$total_pages} 页 ";

                    $page_banner.="<form  class='btn-submit' style='display:inline-block;' action='article.manage.php' method='get'>
                    到第 <input type='text' size='3' name='p'> 页
                    <input type='submit' value='确定'>
                        </form>";
                    echo $page_banner;

                    ?>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</div>
<div class="footer"><strong>版权所有&copy;</strong></div>
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--       $("div.squar-num a").each(function(index){-->
<!--           $(this).click(function(){-->
<!--               alert('hkf');-->
<!--               $(".squar-num a").removeClass("bgclick");-->
<!--               $("a").eq(index).addClass("bgclick");-->
<!--           });-->
<!--       })-->
<!---->
<!--    });-->
<!--</script>-->
</body>
</html>

