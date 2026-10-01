<h2 style="margin-bottom: 20px;">QUẢN LÝ NGƯỜI DÙNG</h2>

<table>
    <tr>
        <th>Tên Đăng Nhập</th>
        <th>Vai Trò (Quyền)</th>
        <th>Thao tác</th>
    </tr>
    <?php
        include_once("Controller/cUser.php");
        $cUser = new controlUser();
        $listUser = $cUser->cGetAllUser();

        if($listUser && $listUser->num_rows > 0) {
            while($row = $listUser->fetch_assoc()) {
                // Đổi IDRole thành chữ cho dễ hiểu
                $roleName = "Khách hàng";
                if ($row['IDRole'] == 1) $roleName = "<strong style='color:red;'>Admin</strong>";
                if ($row['IDRole'] == 2) $roleName = "<strong style='color:blue;'>Staff</strong>";

                echo "<tr>";
                echo "<td>" . $row['Username'] . "</td>";
                echo "<td>" . $roleName . "</td>";
                echo "<td>
                        <a href='#' class='btn btn-delete'>Khóa tài khoản</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Không có người dùng nào.</td></tr>";
        }
    ?>
</table>