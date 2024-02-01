<?php
include("../include/connect.inc.php");
session_start();
$user = $_SESSION['User'];
$companyId = $user->companyId;
$page = $_POST['page'];
$year = date('Y') + 543;
$month = date('m');
$startYear = 2566;
$date = date('Y-m-d');
$dateS = $date . "%";

if ($page == "home") {
    $sqlLog = "SELECT * FROM log WHERE date LIKE '$dateS' ";
    $qLog = $conn->query($sqlLog);
    $qtyUp = 0;
    $qtyDown = 0;
    while ($dataLog = $qLog->fetch_object()) {
        if ($dataLog->status == "up") {
            $qtyUp += $dataLog->qty;
        } else {
            $qtyDown += $dataLog->qty;
        }
    }
    ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow cardColor text-light">
                    <div class="card-body">
                        <h3 class="card-title">รายละเอียด วันนี้</h3>
                        <hr>
                        <div class="d-flex">รายการสินค้า เข้าจำนวน: <span class="ms-4">
                                <h5>
                                    <?php echo $qtyUp ?>
                                </h5>
                            </span></div>
                        <div class="d-flex">รายการสินค้า ออกจำนวน: <span class="ms-3">
                                <h5>
                                    <?php echo $qtyDown ?>
                                </h5>
                            </span></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
<?php }
if ($page == "Product") {
    $stept = 1;
    $sqlPro = "SELECT * FROM product LIMIT 25";
    $qPro = $conn->query($sqlPro);
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <p class="me-auto my-auto">รายการสินค้าที่มีอยู่</p>
                        <button class="btn btn-warning" id="productIn">เพิ่มสินค้า</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tableProduct">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ชื่อสินค้า</th>
                                    <th scope="col">ราคาสินค้า</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="contentTable">
                                <?php
                                $i = 1;
                                while ($data = $qPro->fetch_object()) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $i ?>
                                        </td>
                                        <td>
                                            <?php echo $data->productName; ?>
                                        </td>
                                        <td>
                                            <?php echo $data->price ?>
                                        </td>
                                        <td><button class="btn btn-warning" data-id="<?php echo $data->id ?>"
                                                id="productEdit">แก้ไข</button></td>
                                        <td><button class="btn btn-danger" data-id="<?php echo $data->id ?>"
                                                id="productDel">ลบ</button></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function () {
            let table = new DataTable('#tableProduct');

        })

        $(document).on("click", "#productDel", function () {
            Swal.fire({
                title: "ยืนยันที่จะลบหรือไม่",
                showCancelButton: true,
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var productId = $(this).data("id");
                    var formdata = new FormData();
                    formdata.append("productId",productId);
                    $.ajax({
                        url: "../backend/del.product.php",
                        type: "POST",
                        data: formdata,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            
                            if (data == 1) {
                                Swal.fire({
                                    title: "ลบสินค้าเสร็จสิ้น",
                                    icon:"success",
                                    showConfirmButton: false,
                                    timer:800
                                }).then((result) => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "เกิดข้อผิดพลาด",
                                    icon:"error",
                                    showConfirmButton: false,
                                    timer:800
                                });
                            }

                        }
                    });
                }
            });

        })

        $(document).on("click", "#productEdit", function () {
            var productId = $(this).data("id");
            var formdata = new FormData();
            formdata.append("productId", productId);
            $.ajax({
                url: "edit.product.php",
                type: "POST",
                data: formdata,
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                   Swal.fire({
                    title:"แก้ไขข้อมูลสินค้า",
                    html:data,
                    showConfirmButton:false
                   })
                }
            });

        });

        $(document).on("click", "#productIn", function () {
            Swal.fire({
                title: "เพิ่มรายการสินค้า",
                html: '<input type="text" class="form-control" id="productName" placeholder="ขิื่อสินค้า">' +
                    '<input type="text" class="form-control my-3" id="price" placeholder="ราคาสินค้า">' +
                    '<textarea id="description" class="form-control mb-3" placeholder="รายละเอียดสินค้า" cols="30" rows="5"></textarea>' +
                    '<button class="btn btn-success form-control" id="subPro">ยืนยัน</button>',
                showConfirmButton: false


            })
        });

        $(document).on("click", "#subPro", function () {
            var productName = $('#productName').val();
            var price = $('#price').val();
            var description = $('#description').val();
            var formdata = new FormData();
            formdata.append("productName", productName);
            formdata.append("price", price);
            formdata.append("description", description);
            $.ajax({
                url: "../backend/insert.product.php",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 1) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "เสร็จสิ้น",
                            showConfirmButton: false,
                            timer: 900
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "เกิดข้อผิดพลาด",
                            showConfirmButton: false,
                            timer: 900
                        });
                    }
                }
            })
        });

        $(document).on("click", "#subEdit", function () {
            var productId = $(this).data("id");
            var productName = $('#productName').val();
            var price = $('#price').val();
            var description = $('#description').val();
            var formdata = new FormData();
            formdata.append("productName", productName);
            formdata.append("price", price);
            formdata.append("description", description);
            formdata.append("productId", productId)
            $.ajax({
                url: "../backend/edit.product.php",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data == 1) {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "เสร็จสิ้น",
                            showConfirmButton: false,
                            timer: 900
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "เกิดข้อผิดพลาด",
                            showConfirmButton: false,
                            timer: 900
                        });
                    }
                }
            })
        });

        $(document).on("input", "#search", function () {
            var like = $(this).val();
            var formdata = new FormData();
            formdata.append("like", like);
            $.ajax({
                url: "search.table.php",
                type: "POST",
                data: formdata,
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#contentTable').html(data);
                }
            })
        });
    </script>
<?php }
if ($page == "InUp") {
    $sqlPro = "SELECT * FROM product";
    $qPro = $conn->query($sqlPro);
    ?>
    <div class="row">
        <div class="col-12">
            <div class="container">
                <div class="card mt-4">
                    <div class="card-body">
                        <table class="table" id="tableInUp">
                            <thead>
                                <th scope="col">#</th>
                                <th scope="col">ชิ่อสินค้า</th>
                                <th scope="col">ราคา</th>
                                <th scope="col">จำนวนสินค้า</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </thead>
                            <tbody id="contentTable">
                                <?php
                                $i = 1;
                                while ($data = $qPro->fetch_object()) {
                                    $sqlStock = "SELECT id,qty FROM stock WHERE companyId='$companyId' AND productId='$data->id'";
                                    $qStock = $conn->query($sqlStock);
                                    $rStock = $qStock->num_rows;
                                    if ($rStock == 0) {
                                        $totalQty = 0;
                                        $stockId = "non";
                                    } else {
                                        $dataStock = $qStock->fetch_object();
                                        $totalQty = $dataStock->qty;
                                        $stockId = $dataStock->id;
                                    }

                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $i ?>
                                        </td>
                                        <td>
                                            <?php echo $data->productName; ?>
                                        </td>
                                        <td>
                                            <?php echo $data->price; ?>
                                        </td>
                                        <td>
                                            <?php echo $totalQty; ?>
                                        </td>
                                        <?php
                                        if ($user->status != 1) {
                                            ?>
                                            <td><button class="btn btn-success" id="AddInUp" data-id="<?php echo $stockId ?>"
                                                    data-productid="<?php echo $data->id; ?>">เพิ่ม</button></td>
                                            <td><button class="btn btn-danger" id="DownInUp" data-id="<?php echo $stockId ?>"
                                                    data-productid="<?php echo $data->id; ?>">ลด</button></td>
                                        <?php } ?>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            let table = new DataTable('#tableInUp');

        })
        $(document).on("input", "#search", function () {
            var like = $(this).val();
            var formdata = new FormData();
            formdata.append("like", like);
            $.ajax({
                url: "search.stock.php",
                type: "POST",
                data: formdata,
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#contentTable').html(data);
                }
            })
        });

        $(document).on("click", "#AddInUp", function () {
            var stockId = $(this).data("id");
            var productId = $(this).data("productid");
            Swal.fire({
                position: "center",
                title: "ใส่จำนวนที่ต้องการเพิ่มในคลัง",
                html: '<input type="number" value="0" min="0" max="10" class="form-control" id="qty">' +
                    '<input type="hidden" value="up" id="option">' +
                    '<input type="hidden" value=' + stockId + ' id="stockId">' +
                    '<input type="hidden" value=' + productId + ' id="productId">' +
                    '<button class="btn btn-success mt-3" id="subqty">ยืนยัน</button>',
                showConfirmButton: false
            });
        });

        $(document).on("click", "#DownInUp", function () {
            var stockId = $(this).data("id");
            var productId = $(this).data("productid");
            Swal.fire({
                position: "center",
                title: "ใส่จำนวนที่ต้องการลดในคลัง",
                html: '<input type="number" value="0" min="0" max="10" class="form-control" id="qty">' +
                    '<input type="hidden" value="down" id="option">' +
                    '<input type="hidden" value=' + stockId + ' id="stockId">' +
                    '<input type="hidden" value=' + productId + ' id="productId">' +
                    '<button class="btn btn-success mt-3" id="subqty">ยืนยัน</button>',
                showConfirmButton: false
            });

        });

        $(document).on("click", "#subqty", function () {
            var qty = $('#qty').val();
            var option = $('#option').val();
            var productId = $('#productId').val();
            var stockId = $('#stockId').val();
            var formdata = new FormData();
            formdata.append("qty", qty);
            formdata.append("option", option);
            formdata.append("productId", productId);
            formdata.append("stockId", stockId);
            $.ajax({
                url: "../backend/InUp.stock.php",
                type: "POST",
                data: formdata,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log(data);
                    if (data == 1) {
                        Swal.fire({
                            position: "top-end",
                            title: "เสร็จสิ้น",
                            icon: "success",
                            timer: 800,
                            showConfirmButton: false,
                        }).then((result) => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "เกิดข้อผิดพลาด",
                            timer: 900,
                            showConfirmButton: false
                        });
                    }
                }
            })
        });
    </script>
<?php }
if ($page == "user") { ?>
    <div class="container me-5 ">
        <label for="" class="form-label">สาขา</label>
        <select class="form-select form-select-lg" id="companyId">
            <option selected>โปรดเลือกสาขาที่ต้องการดู</option>
            <?php
            if ($user->status == 9) {
                $sqlUser = "SELECT * FROM company";
            }
            if ($user->status == 5) {
                $sqlUser = "SELECT * FROM company WHERE companyId='$user->companyId'";
            }

            $qUser = $conn->query($sqlUser);
            while ($dataUser = $qUser->fetch_object()) { ?>
                <option value="<?php echo $dataUser->companyId ?>">
                    <?php echo $dataUser->companyName ?>
                </option>
                <?php
            }
            ?>
        </select>

        <button class="btn btn-warning mt-2 form-control" id="insertUser">เพิ่มผู้ใข้งาน</button>
        <hr>

        <div class="container mt-5">
            <div id="iCen2">

            </div>
        </div>
    </div>

    <script>
        $(document).on("click", "#subInUser", function () {
            var firstname = $('#firstname').val();
            var lastname = $('#lastname').val();
            var email = $('#email').val();
            var password1 = $('#password1').val();
            var password2 = $('#password2').val();
            var companyId = $('#companyIdIn').val();
            var statusId = $('#statusId').val();
            console.log(firstname, lastname, email, password1, password2,
                companyId, statusId);
            var formdata = new FormData();
            formdata.append("firstname", firstname);
            formdata.append("lastname", lastname);
            formdata.append("email", email);
            formdata.append("password", password1);
            formdata.append("companyId", companyId);
            formdata.append("statusId", statusId);

            if (password1 == password2) {
                $.ajax({
                    url: "../backend/insert.User.php",
                    type: "POST",
                    data: formdata,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data == "1") {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "เสร็จสิ้น",
                                timer: 900,
                                showConfirmButton: false
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                position: "top-end",
                                icon: "error",
                                title: "เกิดข้อผิดพลาด",
                                timer: 900,
                                showConfirmButton: false
                            })
                        }
                    }
                })
            } else {
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "password ไม่ตรงกัน",
                    showConfirmButton: false,
                    timer: 900
                });
            }
        });

        $(document).on("click", "#insertUser", function () {
            $.ajax({
                url: "insert.User.php",
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                    Swal.fire({
                        title: "เพิ่มผู้ใช้งาน",
                        showConfirmButton: false,
                        html: data
                    });
                }
            })
        })
        $(document).on("input", "#companyId", function () {
            var companyId = $(this).val();
            var formdata = new FormData();
            formdata.append("companyId", companyId);

            $.ajax({
                url: "data.User.php",
                type: "POST",
                data: formdata,
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#iCen2').html(data);
                }
            })
        })
    </script>
<?php }
if ($page == "company") { ?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-6">
                <div class="d-flex">
                    <label for="" class="form-label me-2 my-auto">ปี:</label>
                    <select class="form-select form-select-lg" id="checkCompanyY">
                        <option selected>โปรดเลือก</option>

                        <?php $i = $startYear;
                        while ($i <= $year) { ?>
                            <option value="<?php echo $i - 543; ?>">
                                <?php echo $i; ?>
                            </option>
                            <?php $i++;
                        } ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="d-flex">
                    <label for="" class="form-label me-2 my-auto">เดือน:</label>
                    <select class="form-select form-select-lg" id="checkCompanyM">
                        <option selected>โปรดเลือก</option>
                        <?php $i = 1;
                        while ($i <= 12) { ?>
                            <option value="<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </option>
                            <?php $i++;
                        } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="container">
                <button class="btn btn-success form-control" id="report">รายงาน</button>
                <hr>
            </div>

            <div id="icenReport">

            </div>

        </div>

    </div>
    <script>
        $(document).on("click", "#report", function () {
            var year = $('#checkCompanyY').val();
            var month = $('#checkCompanyM').val();
            var formdata = new FormData();
            formdata.append("year", year);
            formdata.append("month", month);
            $.ajax({
                url: "report.company.php",
                type: "POST",
                data: formdata,
                dataType: "html",
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#icenReport').html(data);

                }
            })
        });
    </script>
<?php }
if ($page == "description") {
    ?>

<?php }
?>