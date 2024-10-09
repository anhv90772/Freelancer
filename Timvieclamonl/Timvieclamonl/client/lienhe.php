<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');
?>
<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php'); // Kết nối cơ sở dữ liệu

// Kiểm tra nếu biểu mẫu được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ biểu mẫu
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Chuẩn bị câu lệnh SQL để lưu dữ liệu vào bảng ticket
    $sql = "INSERT INTO ticket (phone, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $phone, $email, $message);

    // Thực hiện câu lệnh và kiểm tra kết quả
    if ($stmt->execute()) {
        echo "<script>alert('Gửi thông tin hỗ trợ thành công!');</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra khi gửi thông tin!');</script>";
    }
}
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <!-- Fontawesome icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  </head>
  <body>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap');

*{
    box-sizing: border-box;
    padding: 0;
    margin: 0;
}
body{
    font-family: 'Open Sans', sans-serif;
    line-height: 1.5;
}
.contact-bg{
    height: 40vh;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url(image/contact-bg.jpg);
    background-position: 50% 100%;
    background-repeat: no-repeat;
    background-attachment: fixed;
    text-align: center;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.contact-bg h3{
    font-size: 1.3rem;
    font-weight: 400;
}
.contact-bg h2{
    font-size: 3rem;
    text-transform: uppercase;
    padding: 0.4rem 0;
    letter-spacing: 4px;
}
.line div{
    margin: 0 0.2rem;
}
.line div:nth-child(1),
.line div:nth-child(3){
    height: 3px;
    width: 70px;
    background: #f7327a;
    border-radius: 5px;
}
.line{
    display: flex;
    align-items: center;
}
.line div:nth-child(2){
    width: 10px;
    height: 10px;
    background: #f7327a;
    border-radius: 50%;
}
.text{
    font-weight: 300;
    opacity: 0.9;
}
.contact-bg .text{
    margin: 1.6rem 0;
}
.contact-body{
    max-width: 1320px;
    margin: 0 auto;
    padding: 0 1rem;
}
.contact-info{
    margin: 2rem 0;
    text-align: center;
    padding: 2rem 0;
}
.contact-info span{
    display: block;
}
.contact-info div{
    margin: 0.8rem 0;
    padding: 1rem;
}
.contact-info span .fas{
    font-size: 2rem;
    padding-bottom: 0.9rem;
    color: #f7327a;
}
.contact-info div span:nth-child(2){
    font-weight: 500;
    font-size: 1.1rem;
}
.contact-info .text{
    padding-top: 0.4rem;
}
.contact-form{
    padding: 2rem 0;
    border-top: 1px solid #c7c7c7;
}
.contact-form form{
    padding-bottom: 1rem;
}
.form-control{
    width: 100%;
    border: 1.5px solid #c7c7c7;
    border-radius: 5px;
    padding: 0.7rem;
    margin: 0.6rem 0;
    font-family: 'Open Sans', sans-serif;
    font-size: 1rem;
    outline: 0;
}
.form-control:focus{
    box-shadow: 0 0 6px -3px rgba(48, 48, 48, 1);
}
.contact-form form div{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    column-gap: 0.6rem;
}
.send-btn{
    font-family: 'Open Sans', sans-serif;
    font-size: 1rem;
    text-transform: uppercase;
    color: #fff;
    background: #f7327a;
    border: none;
    border-radius: 5px;
    padding: 0.7rem 1.5rem;
    cursor: pointer;
    transition: all 0.4s ease;
}
.send-btn:hover{
    opacity: 0.8;
}
.contact-form > div img{
    width: 85%;
}
.contact-form > div{
    margin: 0 auto;
    text-align: center;
}
.contact-footer{
    padding: 2rem 0;
    background: #000;
}
.contact-footer h3{
    font-size: 1.3rem;
    color: #fff;
    margin-bottom: 1rem;
    text-align: center;
}
.social-links{
    display: flex;
    justify-content: center;
}
.social-links a{
    text-decoration: none;
    width: 40px;
    height: 40px;
    color: #fff;
    border: 2px solid #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0.4rem;
    transition: all 0.4s ease;
}
.social-links a:hover{
    color: #f7327a;
    border-color: #f7327a;
}

@media screen and (min-width: 768px){
    .contact-bg .text{
        width: 70%;
        margin-left: auto;
        margin-right: auto;
    }
    .contact-info{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (min-width: 992px){
    .contact-bg .text{
        width: 50%;
    }
    .contact-form{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        align-items: center;
    }
}

@media screen and (min-width: 1200px){
    .contact-info{
        grid-template-columns: repeat(4, 1fr);
    }
}
</style>
    
    <section class = "contact-section">
      <div class = "contact-bg">
        <h3>Liên hệ với chúng tôi</h3>
        <h2>Liên hệ</h2>
        <div class = "line">
          <div></div>
          <div></div>
          <div></div>
        </div>
        <p class = "text">Nếu có câu hỏi nào xin hãy bày tỏ ra xin đừng ngần ngại chúng tôi sẽ giải đáp !!!.</p>
      </div>


      <div class = "contact-body">
       

        <div class = "contact-form">
             <style>
                  .custom-h1 {
    font-size: 2.5rem; /* Kích thước chữ lớn hơn cho h1 */
}

.custom-p {
    font-size: 1.1rem; /* Kích thước chữ lớn hơn cho p */
}

              </style>
           <form method="POST" action="">
        <h1 class="custom-h1">Bạn cần hỗ trợ?</h1>
        <p class="custom-p">Chúng tôi rất hân hạnh được hỗ trợ bạn hãy để lại thông tin cho chúng tôi nhé</p>

        <div>
            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" required>
            <input type="email" name="email" class="form-control" placeholder="Địa chỉ email" required>
        </div>
        <textarea name="message" rows="5" placeholder="Thông tin cần hỗ trợ" class="form-control" required></textarea>
        <input type="submit" class="send-btn btn btn-primary" value="Gửi">
    </form>

          <div>
            <img src = "https://i.imgur.com/6d6XIzS.png" alt = "">
          </div>
        </div>
        <style>
         .contact-section2 {
    background-color: #f7f7f7; 
    border-radius: 10px; /* Bo góc cho phần tử */
    padding: 2rem; /* Padding cho không gian thêm */
    max-width: 600px; /* Giới hạn chiều rộng */
    margin: 2rem auto; /* Căn giữa */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Đổ bóng cho phần tử */
    text-align: left; /* Căn trái cho nội dung */
}

.contact-header2 {
    text-align: center; /* Căn giữa tiêu đề */
    font-size: 2.5rem; /* Kích thước chữ lớn hơn */
    color: #4a4a4a; /* Màu chữ tiêu đề */
    margin-bottom: 1rem; /* Khoảng cách dưới tiêu đề */
}

.contact-details {
    background-color: #ffffff; /* Nền trắng cho phần chi tiết */
    border-radius: 8px; /* Bo góc cho phần chi tiết */
    padding: 1.5rem; /* Padding cho không gian thêm */
}

.contact-item2 {
    display: flex; /* Sử dụng flexbox cho biểu tượng và nội dung */
    align-items: center; /* Căn giữa dọc */
    margin-bottom: 1rem; /* Khoảng cách giữa các mục */
    padding: 0.5rem; /* Padding cho mỗi mục */
    border: 1px solid #ccc; /* Đường viền cho mỗi mục */
    border-radius: 5px; /* Bo góc cho mỗi mục */
    transition: transform 0.2s; /* Hiệu ứng chuyển động */
}

.contact-item2:hover {
    transform: scale(1.02); /* Hiệu ứng phóng to khi hover */
}

.contact-item2 i {
    font-size: 1.5rem; /* Kích thước biểu tượng */
    color: #007bff; /* Màu biểu tượng */
    margin-right: 10px; /* Khoảng cách giữa biểu tượng và chữ */
}


        </style>
        <hr>
<div class="contact-section2">
    <h1 class="contact-header2">Liên hệ với chúng tôi</h1>
    <div class="contact-details2">
        <div class="contact-item2">
            <i class="fas fa-envelope"></i>
<strong>Email:</strong> <span><?php echo htmlspecialchars($thongtinweb['admin_email']); ?></span>
        </div>
        <div class="contact-item2">
            <i class="fas fa-phone-alt"></i>
            <strong>Số điện thoại: </strong> <?php echo htmlspecialchars($thongtinweb['admin_phone']); ?>
        </div>
        <div class="contact-item2">
            <i class="fas fa-clock"></i>
            <strong>Thời gian làm việc:</strong> <?php echo htmlspecialchars($thongtinweb['timelamviec']); ?>
        </div>
    </div>
</div>








      </div>

    
      </div>
    </section>

     <?php
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/footer.php');
?>

  </body>
</html>