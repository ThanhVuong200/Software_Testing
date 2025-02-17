<?php
session_start();
$pageTitle = "GIỎ HÀNG | DÉP MWC - Hệ thống dép chính hãng";
function customPageHeader(){
    global $pageTitle;
    echo "<title>$pageTitle</title>";
}
include_once 'config.php';
include 'header.php';

// Kiểm tra xem session có chứa giỏ hàng hay không
$sId = session_id(); // lấy session ID

// Xử lý khi xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['id_cart'])) {
    $id_cart = $_GET['id_cart']; // lấy giá trị tham số id_cart
    $query = mysqli_query($conn, "SELECT `soLuongSanPham`, `maSanPham`, `sessionID` FROM `tbl_giohang` WHERE `sessionID`='$sId' AND `maSanPham`='$id_cart'");
    $rows = mysqli_fetch_assoc($query);

    if ($id_cart == $rows['maSanPham']) {
        $deleteQuery = mysqli_query($conn, "DELETE FROM `tbl_giohang` WHERE `maSanPham`='$id_cart' AND `sessionID`='$sId'");
        if ($deleteQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    }
}

// Xử lý cộng và trừ sản phẩm
if (isset($_GET['maSPTru']) && isset($_GET['soluonght'])) {
    $maSPTru = $_GET['maSPTru'];
    $soluonght = $_GET['soluonght'];

    $queryTru = mysqli_query($conn, "SELECT `soLuongSanPham`, `maSanPham`, `sessionID` FROM `tbl_giohang` WHERE `sessionID`='$sId' AND `maSanPham`='$maSPTru'");
    $rows = mysqli_fetch_assoc($queryTru);

    if ($soluonght > 1) {
        $updateQuery = mysqli_query($conn, "UPDATE tbl_giohang SET soLuongSanPham = $soluonght - 1 WHERE `sessionID`='$sId' AND `maSanPham`='$maSPTru'");
        if ($updateQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    } else {
        // Nếu số lượng = 1, xóa sản phẩm khỏi giỏ hàng
        $deleteQuery = mysqli_query($conn, "DELETE FROM `tbl_giohang` WHERE `maSanPham`='$maSPTru' AND `sessionID`='$sId'");
        if ($deleteQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    }
}

if (isset($_GET['maSPCong']) && isset($_GET['soluonght'])) {
    $maSPCong = $_GET['maSPCong'];
    $soluonght = $_GET['soluonght'];

    // Lấy số lượng sản phẩm còn lại trong kho
    $querySLHC = mysqli_query($conn, "SELECT `soLuongSanPham` FROM `tbl_sanpham` WHERE `maSanPham`='$maSPCong'");
    $resultSLHC = mysqli_fetch_assoc($querySLHC);
    $soluonghienco = $resultSLHC['soLuongSanPham'];

    // Kiểm tra số lượng trong giỏ hàng với số lượng trong kho
    if ($soluonght < $soluonghienco) {
        $updateQuery = mysqli_query($conn, "UPDATE tbl_giohang SET soLuongSanPham = $soluonght + 1 WHERE `sessionID`='$sId' AND `maSanPham`='$maSPCong'");
        if ($updateQuery) {
            echo "<script>window.location = 'cart.php';</script>";
        }
    } else {
        echo "<script>alert('Số lượng trong giỏ hàng vượt quá số lượng tồn kho!'); window.location = 'cart.php';</script>";
    }
}
?>

<!-- MAIN CONTENT SECTION -->
<section class="main-content-section">
    <div class="container">
        <h2 class="page-title">GIỎ HÀNG</h2>
        <div class="table-responsive">
            <table class="table table-bordered" id="cart-summary">
                <thead>
                    <tr>
                        <th class="cart-product">Sản phẩm</th>
                        <th class="cart-description">Miêu tả sản phẩm</th>
                        <th class="cart-avail text-center">Tình trạng hàng</th>
                        <th class="cart-unit text-right">Đơn giá</th>
                        <th class="cart_quantity text-center">Số lượng</th>
                        <th class="cart-delete">&nbsp;</th>
                        <th class="cart-total text-right">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Truy vấn tất cả sản phẩm trong giỏ hàng của người dùng
                    $danhsach = mysqli_query($conn, "SELECT *, SUM(`soLuongSanPham`) FROM `tbl_giohang` WHERE `sessionID`='$sId' GROUP BY `maSanPham`");
                    $sup_total = 0;
                    while ($rows = mysqli_fetch_assoc($danhsach)) {
                    ?>
                    <tr>
                        <td class="cart-product">
                            <a href="#"><img alt="Blouse" src="admin/pages/uploads/<?php echo $rows['hinhAnhSanPham']; ?>"></a>
                        </td>
                        <td class="cart-description">
                            <p class="product-name"><a href="#"><?php echo $rows['tenSanPham']; ?></a></p>
                        </td>
                        <td class="cart-avail">
                            <span class="label label-success">Còn hàng</span>
                        </td>
                        <td class="cart-unit">
                            <ul class="price text-right">
                                <li class="price special-price"><?php echo number_format($rows['giaSanPham']); ?> VNĐ</li>
                            </ul>
                        </td>
                        <td class="cart_quantity text-center">
                            <input class="cart-plus-minus" type="text" name="quantybutton" value="<?php echo $rows['SUM(`soLuongSanPham`)']; ?>" readonly="readonly">
                            <a href="?maSPTru=<?php echo $rows['maSanPham']; ?>&soluonght=<?php echo $rows['soLuongSanPham']; ?>">
                                <div class="dec qtybutton" name="dec">-</div>
                            </a>
                            <a href="?maSPCong=<?php echo $rows['maSanPham']; ?>&soluonght=<?php echo $rows['soLuongSanPham']; ?>">
                                <div class="inc qtybutton" name="inc">+</div>
                            </a>
                        </td>
                        <td class="cart-delete text-center">
                            <a href="?id_cart=<?php echo $rows['maSanPham']; ?>" class="cart_quantity_delete" title="Xóa">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                        <td class="cart-total">
                            <?php 
                            $total = $rows['giaSanPham'] * $rows['SUM(`soLuongSanPham`)'];
                            $sup_total += $total;
                            ?>
                            <span class="price"><?php echo number_format($total); ?> VNĐ</span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="cart-total-price">
                        <td class="setup" colspan="3" rowspan="4"></td>
                        <td class="text-right" colspan="3">Tổng thanh toán:</td>
                        <td id="total_product" class="price" colspan="1"><?php echo number_format($sup_total); ?> VNĐ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="returne-continue-shop">
            <?php
            if (isset($_SESSION['soluong']) && $_SESSION['soluong'] > 0) {
                if (isset($_SESSION['ten'])) {
                    echo '<a href="checkout-address.php" class="continueshoping"><input type="submit" class="procedtocheckout" value="Thanh Toán" style="color: white;"></a>';
                } else {
                    echo '<a href="registration.php" class="continueshoping"><input type="submit" class="procedtocheckout" value="Thanh Toán" style="color: white;"></a>';
                }
            } else {
                echo '<input type="button" class="procedtocheckout" value="Thanh Toán" style="color: white;" onClick="alert(\'Chưa có sản phẩm trong giỏ hàng\')">';
            }
            ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Include JS files -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jquery.bxslider.min.js"></script>
<script src="js/jquery.meanmenu.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.nivo.slider.js"></script>
<script src="js/jqueryui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/wow.js"></script>
<script>new WOW().init();</script>
<script src="js/main.js"></script>
</body>
</html>
