<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/all.min.css">
<script src="js/bootstrap.bundle.min.js"> </script>
<nav class="navbar fixed-top"
    style="background-image: linear-gradient(to right, #1976d2, #1488CC, #2B32B2); font-size: 20px;">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link active custom-color" aria-current="page" href="home.php">Andy Coffee & Friends</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="Index.php">POS</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="menu_edit_menu.php">Menu</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                aria-expanded="false">Sale</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="sale_daily.php">สรุปบิลประจำวัน</a></li>
                <li><a class="dropdown-item" href="sale_monthly.php">สรุปบิลประจำเดือน</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="sale_report.php">สรุปการขายสินค้า</a></li>
            </ul>
        </li>
    </ul>

    <div class="text-kanit" style="text-align: right; color: #FFFFFF; margin-right: 20px; ">
        <a href="logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt" style="font-size: 40px; color: #dc3545; "></i>
            <span class="logout-text"><strong>Logout</strong></span>
        </a>
        <style>
            .logout-link {
                text-decoration: none;
                display: inline-block;
                position: relative;
            }

            .logout-text {
                opacity: 0;
                transition: opacity 0.3s, transform 0.3s;
                /* เพิ่มการ transition ใน transform */
                position: absolute;
                bottom: 50%;
                left: calc(-100% - 10px);
                transform: translateY(50%) translateX(-60%);
                /* เลื่อนข้อความออกจากด้านขวา */
                color: #dc3545;
                background-color: white;
                padding: 2px 8px;
                font-size: 1.1em;
                border-radius: 10px;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }

            .logout-link:hover .logout-text {
                opacity: 1;
                left: -100%
                    /* เลื่อนข้อความกลับมาแสดง */
            }

            .logout-link:hover .fas.fa-sign-out-alt {
                transform: translateX(5px);
                transition: transform 0.3s;
            }
        </style>
    </div>
</nav>