<?php  
    include("../include/connect.inc.php");
    session_start();
    $user = $_SESSION['User'];
    $year = $_POST['year'];
    $month = $_POST['month'];

    $date = $year."-".$month;
    $sDate = $date."%";
    
    ?>
    
    <div class="card">
        <div class="card-header">
            <h4>รายงานของ <?php echo $date; ?></h4>
        </div>
        <div class="card-body">
        <table class="table" id="tableCompany">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ชื่อสินค้า</th>
                    <th scope="col">วันที่</th>
                    <th scope="col">เวลา</th>
                    <th scope="col">จำนวน</th>
                    <th scope="col">สถานะ</th>

                </tr>
            </thead>
            <tbody>
        </div>
    </div>
    
       
                
    
    <?php
        $sqlSel = "SELECT * FROM log WHERE date LIKE '$sDate'";
        $qSel = $conn->query($sqlSel);
        $i = 0;
        while($data = $qSel->fetch_object()){ 
            $sqlPro = "SELECT * FROM product WHERE id='$data->productId'";
            $qPro = $conn->query($sqlPro);
            $dataPro = $qPro->fetch_object();
            ?>
            
             <tr>
                    <td scope="row"><?php echo $i ?></td>
                    <td><?php echo $dataPro->productName; ?></td>
                    <td><?php echo $data->date; ?></td>
                    <td><?php echo $data->time; ?></td>
                    <td><?php echo $data->qty; ?></td>
                    <td><?php echo $data->status; ?></td>
            </tr>
        <?php
        $i++;
        }
        ?>
            </tbody>
        </table>

<script>
    $(document).ready(function(){
        let table = new DataTable('#tableCompany');

    })
</script>