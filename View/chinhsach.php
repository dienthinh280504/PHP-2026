<?php
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'giaohang';
?>

<h2 style="margin-bottom:25px; border-bottom:2px solid #eee; padding-bottom:10px; text-transform:uppercase;">
    CHÍNH SÁCH CỬA HÀNG
</h2>

<!-- Tab điều hướng -->
<div style="display:flex; gap:10px; margin-bottom:25px; flex-wrap:wrap;">
    <a href="index.php?page=chinhsach&tab=giaohang" style="text-decoration:none;">
        <button style="padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; font-size:13px;
            background:<?php echo $tab=='giaohang' ? '#000' : '#f4f4f4'; ?>;
            color:<?php echo $tab=='giaohang' ? '#fff' : '#333'; ?>;">
            🚚 Giao hàng
        </button>
    </a>
    <a href="index.php?page=chinhsach&tab=doitra" style="text-decoration:none;">
        <button style="padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; font-size:13px;
            background:<?php echo $tab=='doitra' ? '#000' : '#f4f4f4'; ?>;
            color:<?php echo $tab=='doitra' ? '#fff' : '#333'; ?>;">
            🔄 Đổi trả
        </button>
    </a>
    <a href="index.php?page=chinhsach&tab=baohanh" style="text-decoration:none;">
        <button style="padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; font-size:13px;
            background:<?php echo $tab=='baohanh' ? '#000' : '#f4f4f4'; ?>;
            color:<?php echo $tab=='baohanh' ? '#fff' : '#333'; ?>;">
            🛡️ Bảo hành
        </button>
    </a>
    <a href="index.php?page=chinhsach&tab=baomat" style="text-decoration:none;">
        <button style="padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; font-size:13px;
            background:<?php echo $tab=='baomat' ? '#000' : '#f4f4f4'; ?>;
            color:<?php echo $tab=='baomat' ? '#fff' : '#333'; ?>;">
            🔒 Bảo mật
        </button>
    </a>
    <a href="index.php?page=chinhsach&tab=thanhvien" style="text-decoration:none;">
        <button style="padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:bold; font-size:13px;
            background:<?php echo $tab=='thanhvien' ? '#000' : '#f4f4f4'; ?>;
            color:<?php echo $tab=='thanhvien' ? '#fff' : '#333'; ?>;">
            👑 Thành viên
        </button>
    </a>
</div>

<!-- Nội dung theo từng tab -->
<div style="line-height:1.8; color:#444; font-size:14px; max-width:800px;">

<?php if ($tab == 'giaohang'): ?>
    <h3 style="margin-bottom:15px;">Chính sách giao hàng</h3>
    <ul style="padding-left:20px;">
        <li>Giao hàng toàn quốc, thời gian dự kiến 2-5 ngày làm việc tùy khu vực.</li>
        <li>Miễn phí giao hàng cho đơn từ 500.000đ trở lên.</li>
        <li>Đơn dưới 500.000đ áp dụng phí giao hàng tiêu chuẩn theo khu vực.</li>
        <li>Khách hàng được kiểm tra ngoại quan sản phẩm trước khi thanh toán (COD).</li>
        <li>Thời gian giao hàng có thể thay đổi vào các dịp lễ, Tết hoặc do yếu tố thời tiết.</li>
    </ul>

<?php elseif ($tab == 'doitra'): ?>
    <h3 style="margin-bottom:15px;">Chính sách đổi trả</h3>
    <ul style="padding-left:20px;">
        <li>Hỗ trợ đổi size/đổi mẫu trong vòng 7 ngày kể từ ngày nhận hàng.</li>
        <li>Sản phẩm đổi trả phải còn nguyên tem mác, chưa qua sử dụng, không bị bẩn hoặc hư hỏng.</li>
        <li>Không áp dụng đổi trả với sản phẩm đang trong chương trình giảm giá đặc biệt (trừ lỗi do nhà sản xuất).</li>
        <li>Chi phí vận chuyển đổi trả do khách hàng chi trả, trừ trường hợp lỗi từ phía cửa hàng.</li>
        <li>Liên hệ trước với cửa hàng để được hướng dẫn quy trình đổi trả cụ thể.</li>
    </ul>

<?php elseif ($tab == 'baohanh'): ?>
    <h3 style="margin-bottom:15px;">Chính sách bảo hành</h3>
    <ul style="padding-left:20px;">
        <li>Bảo hành 30 ngày đối với lỗi keo, lỗi đường may do nhà sản xuất.</li>
        <li>Không bảo hành với các trường hợp hư hỏng do sử dụng sai cách, ngâm nước lâu ngày, hoặc tác động ngoại lực.</li>
        <li>Sản phẩm bảo hành sẽ được sửa chữa hoặc đổi mới tùy tình trạng thực tế.</li>
        <li>Vui lòng giữ hóa đơn/mã đơn hàng để làm căn cứ bảo hành.</li>
    </ul>

<?php elseif ($tab == 'baomat'): ?>
    <h3 style="margin-bottom:15px;">Chính sách bảo mật thông tin</h3>
    <ul style="padding-left:20px;">
        <li>Mọi thông tin cá nhân của khách hàng (họ tên, số điện thoại, địa chỉ) chỉ được sử dụng cho mục đích giao nhận đơn hàng.</li>
        <li>Cửa hàng cam kết không chia sẻ, bán hoặc trao đổi thông tin khách hàng cho bên thứ ba.</li>
        <li>Thông tin đăng nhập (mật khẩu) được lưu trữ và xử lý theo cơ chế bảo mật của hệ thống.</li>
        <li>Khách hàng có quyền yêu cầu chỉnh sửa hoặc xóa thông tin cá nhân đã cung cấp.</li>
    </ul>

<?php elseif ($tab == 'thanhvien'): ?>
    <h3 style="margin-bottom:15px;">Chính sách thành viên</h3>
    <p style="margin-bottom:20px;">Hạng thành viên được tính tự động dựa trên <b>tổng số đôi giày</b> bạn đã mua thành công (không tính đơn bị hủy). Hạng càng cao, ưu đãi càng lớn!</p>

    <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
        <thead>
            <tr style="background:#f9f9f9;">
                <th style="padding:12px; border:1px solid #eee; text-align:left;">Hạng</th>
                <th style="padding:12px; border:1px solid #eee;">Điều kiện</th>
                <th style="padding:12px; border:1px solid #eee; text-align:left;">Ưu đãi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:12px; border:1px solid #eee;">🥉 Đồng</td>
                <td style="padding:12px; border:1px solid #eee; text-align:center;">0 - 4 đôi</td>
                <td style="padding:12px; border:1px solid #eee;">Chưa có ưu đãi đặc biệt</td>
            </tr>
            <tr>
                <td style="padding:12px; border:1px solid #eee;">🥈 Bạc</td>
                <td style="padding:12px; border:1px solid #eee; text-align:center;">5 - 9 đôi</td>
                <td style="padding:12px; border:1px solid #eee;">Giảm 5% mỗi đơn hàng</td>
            </tr>
            <tr>
                <td style="padding:12px; border:1px solid #eee;">🥇 Vàng</td>
                <td style="padding:12px; border:1px solid #eee; text-align:center;">10 - 19 đôi</td>
                <td style="padding:12px; border:1px solid #eee;">Giảm 10% + ưu tiên hỗ trợ</td>
            </tr>
            <tr>
                <td style="padding:12px; border:1px solid #eee;">💎 Kim Cương</td>
                <td style="padding:12px; border:1px solid #eee; text-align:center;">20 đôi trở lên</td>
                <td style="padding:12px; border:1px solid #eee;">Giảm 15% + miễn phí vận chuyển trọn đời</td>
            </tr>
        </tbody>
    </table>

    <p style="font-size:13px; color:#888;">*Hạng thành viên được cập nhật tự động sau mỗi đơn hàng thành công. Xem hạng hiện tại của bạn tại mục "Đơn hàng của tôi".</p>
<?php endif; ?>

</div>