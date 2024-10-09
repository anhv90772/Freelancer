<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');

$user = $_SESSION['user'];
$user_id = $user['id']; // Lấy ID người dùng từ thông tin người dùng

/// Kiểm tra đã điền nghe nghiep và mô tả chưa nếu chưa thì hiện alert
function checkUserInfo($user_id, $conn) {
    $sql = "SELECT nghenghiep, mota FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Trả về thông tin người dùng
}

$user_info = checkUserInfo($user_id, $conn);
$show_alert = false;

if (empty($user_info['nghenghiep']) || empty($user_info['mota'])) {
    $show_alert = true; // Nếu không có thông tin nghề nghiệp hoặc mô tả, đặt cờ để hiển thị thông báo
}
// Xử lý tìm kiếm
$search_query = '';
$field_filter = '';

// Kiểm tra xem có tìm kiếm không
if (isset($_POST['search'])) {
    $search_query = $_POST['search']; // Lưu trữ từ khóa tìm kiếm
    $field_filter = $_POST['field']; // Lưu trữ lĩnh vực tìm kiếm
}

// Xác định trang hiện tại
$limit = 10; // Số bài viết trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
$offset = ($page - 1) * $limit; // Tính toán offset cho truy vấn

// Lấy tổng số bài viết đã được phê duyệt và chưa hết hạn
$current_date = date('Y-m-d'); // Lấy ngày hiện tại
$search_param = mysqli_real_escape_string($conn, "%" . $search_query . "%");
$field_filter_escaped = mysqli_real_escape_string($conn, $field_filter);

// Truy vấn SQL để lấy tổng số bài viết
$total_sql = "SELECT COUNT(*) AS total 
              FROM jobs 
              WHERE duyet = 1 AND job_title LIKE '$search_param' 
              AND (field = '$field_filter_escaped' OR '$field_filter_escaped' = '') 
              AND deadline >= '$current_date'";

$total_result = $conn->query($total_sql);
if (!$total_result) {
    die("Lỗi: " . $conn->error); // Kiểm tra lỗi trong truy vấn
}
$total_row = $total_result->fetch_assoc();
$total_jobs = $total_row['total']; // Tổng số bài viết

// Lấy danh sách bài viết đã được phê duyệt, chưa hết hạn và đếm chào giá cho mỗi bài
$sql = "SELECT j.id, j.job_title, j.user_id, j.field, j.salary_from, j.salary_to, j.deadline, u.fullname, 
               (SELECT COUNT(*) FROM proposals b WHERE b.job_id = j.id) AS bid_count
        FROM jobs j 
        JOIN users u ON j.user_id = u.id 
        WHERE j.duyet = 1 AND j.job_title LIKE '$search_param' 
        AND (j.field = '$field_filter_escaped' OR '$field_filter_escaped' = '') 
        AND j.deadline >= '$current_date'
        ORDER BY j.id DESC 
        LIMIT $offset, $limit"; 

$result = $conn->query($sql);
if (!$result) {
    die("Lỗi: " . $conn->error); // Kiểm tra lỗi trong truy vấn
}

$jobs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row; // Lưu trữ từng công việc vào mảng
    }
}
$conn->close(); // Đóng kết nối cơ sở dữ liệu

// Tính số trang
$total_pages = ceil($total_jobs / $limit); 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Helvetica Neue', sans-serif;
        }
        .container {
            max-width: 900px;
            margin-top: 50px;
            background: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }
        .job-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
        }
        .job-title2 {
            font-size: 30px;
            font-weight: 600;
            color: #004080;
            margin-bottom: 10px;
        }
        .btn-view {
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .btn-view:hover {
            background-color: #0056b3;
        }
        .text-danger {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #888;
            font-size: 14px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        
    </style>
</head>
<body>

<style>
    .slider {
        width: 100vw; /* Chiếm toàn bộ chiều ngang màn hình */
        height: 450px; /* Giữ nguyên chiều cao hoặc điều chỉnh theo nhu cầu */
        margin: auto;
        position: relative;
        overflow: hidden;
    }

    .slider .list {
        position: absolute;
        width: max-content;
        height: 100%;
        left: 0;
        top: 0;
        display: flex;
        transition: 1s;
    }

    .slider .list img {
        width: 100vw; /* Chiếm toàn bộ chiều ngang màn hình */
        height: 100%;
        object-fit: cover; /* Đảm bảo ảnh giữ tỷ lệ và bao phủ khung */
    }

    .slider .buttons {
        position: absolute;
        top: 45%;
        left: 5%;
        width: 90%;
        display: flex;
        justify-content: space-between;
    }

    .slider .buttons button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #fff5;
        color: #fff;
        border: none;
        font-family: monospace;
        font-weight: bold;
    }

    .slider .dots {
        position: absolute;
        bottom: 10px;
        left: 0;
        color: #fff;
        width: 100%;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    .slider .dots li {
        list-style: none;
        width: 10px;
        height: 10px;
        background-color: #fff;
        margin: 10px;
        border-radius: 20px;
        transition: 0.5s;
    }

    .slider .dots li.active {
        width: 30px;
    }

    @media screen and (max-width: 777px) {
        .slider {
            height: 350px; /* Giảm chiều cao trên màn hình nhỏ hơn */
        }

        .slider .list img {
            width: 100vw; /* Vẫn giữ ảnh kéo dài hết màn hình trên các thiết bị nhỏ */
        }
    }
</style>


<div class="slider">
    <div class="list">
        <div class="item">
            <img src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/474096tqT/hinh-nen-powerpoint-chu-de-cong-nghe_042037477.jpg" alt="">
        </div>
        <div class="item">
            <img src="https://i.pinimg.com/736x/c3/5e/3c/c35e3c1baa7be93127b11f5e2de8ba8f.jpg" alt="">
        </div>
        <div class="item">
            <img src="https://png.pngtree.com/thumb_back/fw800/background/20201019/pngtree-abstract-technology-background-with-circle-glow-light-and-vector-illustration-image_421297.jpg" alt="">
        </div>
        <div class="item">
            <img src="https://png.pngtree.com/thumb_back/fw800/background/20190221/ourmid/pngtree-tech-line-background-simple-technology-business-image_23240.jpg" alt="">
        </div>
        <div class="item">
            <img src="https://png.pngtree.com/thumb_back/fw800/back_our/20190622/ourmid/pngtree-blue-technology-internet-background-image_209970.jpg" alt="">
        </div>
    </div>
    <div class="buttons">
        <button id="prev"><</button>
        <button id="next">></button>
    </div>
    <ul class="dots">
        <li class="active"></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div>
 <style>
        .alert-custom {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 300px;
        }
    </style>
   <?php if ($show_alert): ?>
        <div class="alert alert-warning alert-custom" id="alert" role="alert">
            Bạn chưa điền thông tin nghề nghiệp và mô tả. Vui lòng cập nhật thông tin để hoàn tất.
            <button type="button" class="btn-close" aria-label="Close" onclick="closeAlert()"></button>
        </div>
    <?php endif; ?>
    
    
<div class="container">
    <h2>Các Công Việc Mới Nhất</h2>

    <!-- Ô tìm kiếm -->
    <form method="POST" class="mb-4">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề..." value="<?php echo htmlspecialchars($search_query); ?>">
        </div>
        <div class="mb-3">
          <div class="mb-3">
    <select class="form-select" id="field" name="field">
        <option value="" disabled <?php echo ($field_filter === '') ? 'selected' : ''; ?>>Chọn lĩnh vực</option>
        <option value="" <?php echo ($field_filter === '') ? 'selected' : ''; ?>>Tất cả lĩnh vực</option>
        <option value="Công nghệ thông tin" <?php echo ($field_filter === 'Công nghệ thông tin') ? 'selected' : ''; ?>>Công nghệ thông tin</option>
        <option value="Dịch thuật" <?php echo ($field_filter === 'Dịch thuật') ? 'selected' : ''; ?>>Dịch thuật</option>
        <option value="Marketing" <?php echo ($field_filter === 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
        <option value="Kiến trúc" <?php echo ($field_filter === 'Kiến trúc') ? 'selected' : ''; ?>>Kiến trúc</option>
        <option value="Thiết kế" <?php echo ($field_filter === 'Thiết kế') ? 'selected' : ''; ?>>Thiết kế</option>
    </select>
    </div>
        </div>
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>

    </form>

<?php if (count($jobs) > 0): ?>
    <?php foreach ($jobs as $job): ?>
        <div class="job-card">
            <center><div class="job-title2"><?php echo htmlspecialchars($job['job_title']); ?></div></center>
            <p><strong>Người Đăng:</strong> <?php echo htmlspecialchars($job['fullname']); ?></p>
            <p><strong>Lĩnh vực:</strong> <?php echo htmlspecialchars($job['field']); ?></p>
                        <p><strong>Hạn deadline:</strong> <?php echo htmlspecialchars($job['deadline']); ?></p>

            <p class="salary" style="display: inline;">Ngân sách dự kiến: </p>
            <span><?php echo number_format($job['salary_from'], 0, ',', '.'); ?>đ - <?php echo number_format($job['salary_to'], 0, ',', '.'); ?>đ</span>
            </p>
            <p class="salary" style="display: inline;">Số chào giá được gửi: </p>
            <span><?php echo htmlspecialchars($job['bid_count']); ?></span>
            <br>
            <br>
            <center><a href="/client/xemduan.php?id=<?php echo $job['id']; ?>" >Xem Chi Tiết</a></center>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-danger">Không có bài nào.</p>
<?php endif; ?>

<style>
    .job-card {
        background-color: #ffffff; /* Màu nền trắng */
        border: 1px solid #ddd; /* Viền nhẹ */
        border-radius: 8px; /* Bo góc */
        padding: 20px; /* Padding cho nội dung */
        margin: 15px 0; /* Khoảng cách giữa các job-card */
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Bóng đổ lớn hơn */
        transition: transform 0.2s ease-in-out; /* Hiệu ứng chuyển động */
    }

    .job-title {
        font-size: 20px; /* Kích thước chữ tiêu đề */
        font-weight: bold; /* In đậm */
        color: #00274d; /* Màu chữ */
        margin-bottom: 10px; /* Khoảng cách dưới tiêu đề */
    }

    .salary {
        color: #01305d; /* Màu chữ cho ngân sách */
    }

    .view-details {
        text-decoration: none; /* Bỏ gạch chân */
        color: white; /* Màu chữ */
        background-color: #007bff; /* Màu nền cho nút xem chi tiết */
        padding: 10px 15px; /* Padding cho nút */
        border-radius: 5px; /* Bo góc cho nút */
        transition: background-color 0.3s; /* Hiệu ứng chuyển màu nền */
    }

    .view-details:hover {
        background-color: #0056b3; /* Màu nền khi hover */
    }
</style>



    <!-- Phân trang -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>&field=<?php echo urlencode($field_filter); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

  
    <script>
        let slider = document.querySelector('.slider .list');
let items = document.querySelectorAll('.slider .list .item');
let next = document.getElementById('next');
let prev = document.getElementById('prev');
let dots = document.querySelectorAll('.slider .dots li');

let lengthItems = items.length - 1;
let active = 0;
next.onclick = function(){
    active = active + 1 <= lengthItems ? active + 1 : 0;
    reloadSlider();
}
prev.onclick = function(){
    active = active - 1 >= 0 ? active - 1 : lengthItems;
    reloadSlider();
}
let refreshInterval = setInterval(()=> {next.click()}, 3000);
function reloadSlider(){
    slider.style.left = -items[active].offsetLeft + 'px';
    // 
    let last_active_dot = document.querySelector('.slider .dots li.active');
    last_active_dot.classList.remove('active');
    dots[active].classList.add('active');

    clearInterval(refreshInterval);
    refreshInterval = setInterval(()=> {next.click()}, 3000);

    
}

dots.forEach((li, key) => {
    li.addEventListener('click', ()=>{
         active = key;
         reloadSlider();
    })
})
window.onresize = function(event) {
    reloadSlider();
};
    </script>
</div>
<hr>
<br>

<center><h1>Freelancer nổi bật</h1></center>
<style>
    .custom-slider-container {
        overflow: hidden; /* ẩn thanh cuộn */
        width: 100%; /* chiều rộng đầy đủ */
        margin: 0 auto; /* căn giưa */
        text-align: center; /* căn giữa các avatar */
    }

    .custom-slider {
        display: flex;
        flex-wrap: wrap; /* cho phép các thẻ xếp theo hàng */
        justify-content: center; /* căn giữa các avatar trong slider */
        transition: transform 0.5s ease;
    }

    .custom-card {
        background-color: #fff; /* màu nền của card */
        border-radius: 10px; /* bo góc cho card */
        padding: 15px; /* khoảng cách bên trong card */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* đổ bóng */
        margin: 10px; /* khoảng cách giữa các card */
        display: flex;
        flex-direction: column; /* sắp xếp theo cột */
        align-items: center; /* căn giữa các thành phần trong card */
        width: 150px; /* chiều rộng của mỗi card */
    }

    .custom-avatar {
        flex: 0 0 auto;
        margin-bottom: 10px; /* khoảng cách giữa avatar và tên */
    }

    .custom-avatar img {
        width: 100px; /* kích thước avatar */
        height: 100px; /* kích thước avatar */
        border-radius: 50%; /* tạo hình tròn */
        object-fit: cover; /* giữ tỷ lệ hình ảnh */
        border: 2px solid #fff; /* đường viền trắng */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* đổ bóng nhẹ */
        transition: transform 0.3s ease; /* hiệu ứng mượt mà */
    }

    /* Thêm hiệu ứng hover */
    .custom-avatar img:hover {
        transform: scale(1.2); /* phóng to hình ảnh khi hover */
    }

    .member-name {
        font-size: 16px; /* kích thước chữ */
        font-weight: bold; /* chữ đậm */
        color: #333; /* màu chữ */
    }
</style>

<div class="custom-slider-container">
    <div class="custom-slider">
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://img5.thuthuatphanmem.vn/uploads/2021/07/16/anh-trai-dep-10x-viet-nam_085754527.jpg" alt="Thành viên 1">
            </div>
            <div class="member-name">Nguyễn Văn A</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://sacbaongoc.net/wp-content/uploads/2022/08/300-hinh-anh-trai-dep-viet-nam-ngau-cute-lanh-lung-nhat.jpg">
            </div>
            <div class="member-name">Nguyễn Văn B</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://kynguyenlamdep.com/wp-content/uploads/2022/08/anh-trai-dep-che-mat-bang-bo-cuc-hoa-mi.jpg" alt="Thành viên 3">
            </div>
            <div class="member-name">Nguyễn Văn C</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://www.invert.vn/media/uploads/uploads/2022/11/03172452-9-anh-trai-dep-che-mat-bang-filter.jpeg" alt="Thành viên 4">
            </div>
            <div class="member-name">Nguyễn Văn D</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://kynguyenlamdep.com/wp-content/uploads/2022/08/anh-trai-dep-deo-kinh.jpg" alt="Thành viên 5">
            </div>
            <div class="member-name">Nguyễn Văn E</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/10/hinh-anh-trai-xau-nhat.jpg" alt="Thành viên 6">
            </div>
            <div class="member-name">Nguyễn Văn F</div>
        </div>
        <div class="custom-card">
            <div class="custom-avatar">
                <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/10/anh-trai-xau-viet-nam-tu-suong.jpg" alt="Thành viên 7">
            </div>
            <div class="member-name">Nguyễn Văn G</div>
        </div>
    </div>
</div>
<br>
<hr>
<style>
    .styles-0 {
        background-color: rgb(245, 245, 245);
    }

    .styles-1 {
        overflow-wrap: break-word;
        margin: 0px;
        padding-top: 64px;
        font-size: 30px;
        font-weight: 300;
        text-align: center;
        letter-spacing: -1.5px;
        line-height: 32px;
        font-family: 'Open Sans', sans-serif;
        color: rgb(51, 51, 51);
            margin: 0 auto; /* Căn giữa phần tử */
    padding: 0 15px; /* Padding bên trái và bên phải */
        text-rendering: optimizelegibility;
    }

    .styles-2 {
        width: 1200px;
        padding: 40px 0px 68px;
        margin-right: auto;
        margin-left: auto;
    }

    .styles-3 {
        margin-bottom: 40px;
        width: 100%;
    }

    .styles-4 {
        padding-left: 0px;
        width: 23.4043%;
        margin-left: 0px;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
    }

    .styles-5 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px 0px;
    }

    .styles-6 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-7 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-8 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-9 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-10 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-11 {
        margin-top: 14px;
    }

    .styles-12 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-13 {
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-14 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -200px;
    }

    .styles-15 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-16 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-17 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-18 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-19 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-20 {
        margin-top: 14px;
    }

    .styles-21 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-22 {
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-23 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -400px;
    }

    .styles-24 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-25 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-26 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-27 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-28 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-29 {
        margin-top: 14px;
    }

    .styles-30 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-31 {
        text-align: right;
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-32 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -600px;
    }

    .styles-33 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-34 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-35 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-36 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-37 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-38 {
        margin-top: 14px;
    }

    .styles-39 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-40 {
        margin-bottom: 40px;
        width: 100%;
    }

    .styles-41 {
        padding-left: 0px;
        width: 23.4043%;
        margin-left: 0px;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
    }

    .styles-42 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -800px;
    }

    .styles-43 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-44 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-45 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-46 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-47 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-48 {
        margin-top: 14px;
    }

    .styles-49 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-50 {
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-51 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -1000px;
    }

    .styles-52 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-53 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-54 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-55 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-56 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-57 {
        margin-top: 14px;
    }

    .styles-58 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-59 {
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-60 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -1200px;
    }

    .styles-61 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-62 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-63 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-64 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-65 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-66 {
        margin-top: 14px;
    }

    .styles-67 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }

    .styles-68 {
        text-align: right;
        width: 23.4043%;
        display: block;
        min-height: 30px;
        box-sizing: border-box;
        float: left;
        margin-left: 25.5312px;
    }

    .styles-69 {
        display: block;
        background-image: url(&quot;/img/vn/homepage-new/sprite-service-pack.png&quot;);
        background-image: url('https://www.vlance.vn/img/vn/homepage-new/sprite-service-pack.png');
        border: 8px solid rgb(255, 255, 255);
        width: 250px;
        height: 200px;
        overflow: hidden;
        position: relative;
        background-position: 0px -1400px;
    }

    .styles-70 {
        overflow-wrap: break-word;
        color: rgb(0, 136, 204);
        text-decoration: none solid rgb(0, 136, 204);
    }

    .styles-71 {
        transition: 0.2s linear;
        background: rgb(102, 104, 102) none repeat scroll 0% 0% / auto padding-box border-box;
        position: absolute;
        inset: 0px 0px 400px;
        width: 250px;
        height: 200px;
        display: table;
        color: rgb(247, 247, 247);
        text-align: center;
        opacity: 0;
    }

    .styles-72 {
        transition: 0.2s linear;
        display: block;
        vertical-align: middle;
        width: 250px;
        height: 200px;
        text-align: center;
        z-index: 10;
        color: rgb(255, 255, 255);
        position: absolute;
        padding-top: 68px;
        opacity: 0;
    }

    .styles-73 {
        font-size: 16px;
        font-weight: 600;
        box-shadow: none;
        overflow-wrap: break-word;
        padding: 13px 50px;
        margin-bottom: 10px;
        text-shadow: none;
        border: 0px none rgb(51, 51, 51);
        display: inline-block;
        line-height: 20px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        color: rgb(51, 51, 51);
        background-color: rgb(245, 245, 245);
        background-image: linear-gradient(rgb(255, 255, 255), rgb(230, 230, 230));
        background-repeat: repeat-x;
        border-width: 0px;
        border-styles: none;
        border-color: rgb(51, 51, 51);
        border-image: none;
        border-radius: 4px;
    }

    .styles-74 {
        overflow-wrap: break-word;
        display: block;
    }

    .styles-75 {
        margin-top: 14px;
    }

    .styles-76 {
        max-width: 200px;
        margin: auto;
        text-align: center;
    }
</style>
<div class="styles-0">
    <h4 class="styles-1">Công việc được thuê nhiều nhất</h4>
    <div class="styles-2">
        <div class="styles-3">
            <div class="styles-4">
                <div class="styles-5" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-7">
                        </div>
                        <div class="styles-8">
                            <span class="styles-9">Đăng dự án</span> <span class="styles-10">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-11">
                    <p class="styles-12">Thiết kế website</p>
                </div>
            </div>
            <div class="styles-13">
                <div class="styles-14" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-16">
                        </div>
                        <div class="styles-17">
                            <span class="styles-18">Đăng dự án</span> <span class="styles-19">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-20">
                    <p class="styles-21">Dịch thuật</p>
                </div>
            </div>
            <div class="styles-22">
                <div class="styles-23" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-25">
                        </div>
                        <div class="styles-26">
                            <span class="styles-27">Đăng dự án</span> <span class="styles-28">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-29">
                    <p class="styles-30">Lập trình website</p>
                </div>
            </div>
            <div class="styles-31">
                <div class="styles-32" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-34">
                        </div>
                        <div class="styles-35">
                            <span class="styles-36">Đăng dự án</span> <span class="styles-37">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-38">
                    <p class="styles-39">Làm video clip</p>
                </div>
            </div>
        </div>
        <div class="styles-40">
            <div class="styles-41">
                <div class="styles-42" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-44">
                        </div>
                        <div class="styles-45">
                            <span class="styles-46">Đăng dự án</span> <span class="styles-47">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-48">
                    <p class="styles-49">Tối ưu hóa công cụ tìm kiếm seo</p>
                </div>
            </div>
            <div class="styles-50">
                <div class="styles-51" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-53">
                        </div>
                        <div class="styles-54">
                            <span class="styles-55">Đăng dự án</span> <span class="styles-56">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-57">
                    <p class="styles-58">Viết nội dung website</p>
                </div>
            </div>
            <div class="styles-59">
                <div class="styles-60" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-62">
                        </div>
                        <div class="styles-63">
                            <span class="styles-64">Đăng dự án</span> <span class="styles-65">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-66">
                    <p class="styles-67">Thiết kế logo</p>
                </div>
            </div>
            <div class="styles-68">
                <div class="styles-69" data-background="/img/vn/homepage-new/sprite-service-pack.png">
                        <div class="styles-71">
                        </div>
                        <div class="styles-72">
                            <span class="styles-73">Đăng dự án</span> <span class="styles-74">Giá tham khảo 1.000.000 VNĐ</span>
                        </div>
                </div>
                <div class="styles-75">
                    <p class="styles-76">Ứng dụng di động</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function closeAlert() {
        document.getElementById('alert').style.display = 'none';
    }
</script>


 <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>
</body>
</html>
