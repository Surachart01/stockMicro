<?php 
    session_start();
    if(!isset($_SESSION['User'])){
        header("location:login.page.php");
    }else{
        $user = $_SESSION['User'];
        $status = $user->status;
    }
?>
<!doctype html>
<html lang="en">

<head>
  <title>Title</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../include/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<style>
     *{
        font-family: 'Kanit', sans-serif;
    }
    .navbarSi{
        background-color: #212A59;
    }
    .navv{
        background-color: #FFFF;
    }
    .sidebar{
        background-color: #131835;
        height: 90vh;
    }
    .content{
        background-color: #F3F3F3;
    }
    .cd1{
        background-color: #212A59;
        border: 0px;
        border-radius: 15px;
    }
    
</style>
<body>
  <div class="row ">
    <div class="col-2 navbarSi ps-4 text-light py-3">


        {{LOGO Brand}}

        
    </div>
    <div class="col-10 navv pe-4 text-dark py-3">
        <div class="d-flex justify-content-between">
            <div class=""></div>
            <div class="me-3 d-flex ">
                <span class="ms-3 me-4">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $user->firstname ?>
                    </a>
                    <ul class="dropdown-menu"> 
                        <li><a class="dropdown-item" href="logout.page.php">Logout</a></li>
                    </ul>
                </span>
            </div>
        </div>
    </div>
  </div>
  <div class="row">
    <div class="col-2 sidebar">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-light">
        <ul class="nav nav-pills flex-column ">
            <li class="nav-item"><a href="?page=home"  class="nav-link text-light"><i class="bi bi-house-door-fill pe-2 pt-0" style="font-size:20px;"></i> <strong class="pb-0">หน้าหลัก</strong></a></li>
            <?php  
            if($status == "5" || $status == "9" || $status == "1"){
            ?>
                <li class="nav-item"><a href="?page=InUp" class="nav-link text-light" id="InUpPro"><i class="bi bi-box pe-1 " style="font-size:20px;"></i> <strong class="">จัดการสินค้าในคลัง</strong></a></li>
                <hr>
                <?php } ?>
            <?php if($status == "5" || $status == "9"){ ?>
                
                <li class="nav-item"><a href="?page=user" class="nav-link text-light" id="dUser"><i class="bi bi-people-fill pe-2 " style="font-size:20px;"></i> พนักงาน</a></li>
                <li class="nav-item"><a href="?page=company" class="nav-link text-light" id="dUser"><i class="bi bi-building pe-2" style="font-size:20px;"></i> รายงานสรุปสาขา</a></li>
                <hr>
                <?php } ?>
            <?php if($status == "9"){ ?>
                
                <li class="nav-item"><a href="?page=Product"  class="nav-link text-light" id="checkPro"><i class="bi bi-clipboard-check pe-2 " style="font-size:20px;"></i>รายการสินค้า</a></li>
                <hr>
            <?php } ?>
        </ul>
        </div>
    </div>
    <div class="col-10 content pt-2">
        <div id="iCen">
            
        </div>

    </div>
  </div>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <script>
        $(document).ready(function(){
            var urlParams = new URLSearchParams(window.location.search);
            var name = urlParams.get('page');
            page(name);
            $(document).on("click","#cancelDel",function(){
                Swal.fire({
                    title:"ยกเลิกแล้ว",
                    icon:"success",
                    showConfirmButton:false,
                    timer:800
                })
        })

            $(document).on("click","#submitDel",function(){
                var memId = $(this).data("memid");
                var formdata = new FormData();
                formdata.append("memId",memId);
                $.ajax({
                   url:"../backend/del.User.php",
                   type:"POST",
                   data:formdata,
                   dataType:"json",
                   contentType:false,
                   processData:false,
                   success:function(data){
                    console.log(data)
                        if(data == 1){
                            Swal.fire({
                                position:"top-end",
                                title:"ลบเสร็จสิ้น",
                                icon:"success",
                                showConfirmButton:false,
                                timer:800
                            }).then(() => {
                                window.location.reload();
                            });
                        }else{
                            Swal.fire({
                                position:"top-end",
                                title:"เกิดข้อผิดพลาด",
                                icon:"error",
                                showConfirmButton:false,
                                timer:800
                            })
                        }
                   }
                });
            });

            $(document).on("click","#deluser",function(){
                var memId = $(this).data("memid");
                Swal.fire({
                    position:"center",
                    title:"ลบผู้ใช้คนนี้หรือไม่ ?",
                    html:"<hr><div class='d-flex justify-content-center'><button class='btn btn-danger me-4' id='cancelDel'>ยกเลิก</button>"+
                         "<button class='btn btn-success' data-memid='"+memId+"' id='submitDel'>ยืนยัน</button></div>",
                    showConfirmButton:false,
                });
            });

            $(document).on("click","#changeStatus",function(){
                var change = $(this).data("status");
                var memId = $(this).data("memid");
                var formdata = new FormData();
                formdata.append("change",change);
                formdata.append("memId",memId);
                $.ajax({
                    url:"../backend/edit.status.php",
                    type:"POST",
                    data:formdata,
                    dataType:"json",
                    contentType:false,
                    processData:false,
                    success:function(data){
                        if(data == 1){
                            Swal.fire({
                                position:"top-end",
                                title:"แก้ไขเสร็จสิ้น",
                                icon:"success",
                                showConfirmButton:false,
                                timer:800
                            });
                        }else{
                            Swal.fire({
                                position:"top-end",
                                title:"เกิดข้อผิดพลาด",
                                icon:"error",
                                showConfirmButton:false,
                                timer:800
                            });
                        }
                    }
                })
            });
        });

        function page(page){
            var formdata = new FormData();
            formdata.append("page",page);
            $.ajax({
                url:"page.all.php",
                type:"POST",
                data:formdata,
                dataType:"html",
                contentType:false,
                processData:false,
                success:function(data){
                    $('#iCen').html(data);
                }
            })
        }


  </script>

  <!-- Bootstrap JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>