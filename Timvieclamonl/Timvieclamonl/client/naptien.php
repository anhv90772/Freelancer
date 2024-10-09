<?php
session_start();
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/database.php');
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) . '/ketnoi/header.php');
?>
<style>
  body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
    .style-0 {
        margin: 0px;
        padding: 20px 0px;
        box-sizing: border-box;
    }

    .style-1 {
        max-width: 1140px;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        box-sizing: border-box;
    }

    .style-2 {
        border-left-color: rgb(150, 33, 44);
        border-left-width: 2.66667px;
        border-left-style: solid;
        font-size: 13.5px;
        margin-bottom: 10px;
        border-radius: 5px;
        color: rgb(114, 28, 36);
        background-color: rgb(248, 215, 218);
        border-color: rgb(245, 198, 203) rgb(245, 198, 203) rgb(245, 198, 203) rgb(150, 33, 44);
        position: relative;
        padding: 10.5px 17.5px;
        box-sizing: border-box;
    }

    .style-3 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-4 {
        color: #e74c3cbox-sizing:border-box;
        box-sizing: border-box;
    }

    .style-5 {
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-6 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-7 {
        color: #e74c3cbox-sizing:border-box;
        box-sizing: border-box;
    }

    .style-8 {
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-9 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-10 {
        color: #e74c3cbox-sizing:border-box;
        box-sizing: border-box;
    }

    .style-11 {
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-12 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-13 {
        color: #e74c3cbox-sizing:border-box;
        box-sizing: border-box;
    }

    .style-14 {
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-15 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-16 {
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-17 {
        color: #2ecc71box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-18 {
        box-sizing: border-box;
    }

    .style-19 {
        color: #4e5f70box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-20 {
        box-sizing: border-box;
    }

    .style-21 {
        color: #2ecc71box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-22 {
        box-sizing: border-box;
    }

    .style-23 {
        color: #000000box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-24 {
        box-sizing: border-box;
    }

    .style-25 {
        color: #2ecc71box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-26 {
        box-sizing: border-box;
    }

    .style-27 {
        margin-bottom: 5px;
        margin-top: 0px;
        box-sizing: border-box;
    }

    .style-28 {
        color: #000000box-sizing:border-box;
        box-sizing: border-box;
    }

    .style-29 {
        box-sizing: border-box;
    }

    .style-30 {
        margin: 0px;
        padding: 20px 0px;
        box-sizing: border-box;
    }

    .style-31 {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        box-sizing: border-box;
    }

    .style-32 {
        flex: 0 0 50%;
        max-width: 50%;
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        box-sizing: border-box;
    }

    .style-33 {
        margin-bottom: 14px;
        box-sizing: border-box;
    }

    .style-34 {
        font-size: 22px;
        margin-bottom: 10px;
        font-weight: 700;
        color: rgb(17, 17, 17);
        box-sizing: border-box;
    }

    .style-35 {
        box-sizing: border-box;
    }

    .style-36 {
        box-sizing: border-box;
    }

    .style-37 {
        transition: 0.3s ease-in-out;
        overflow: clip;
        margin: 0px;
        font-family: Roboto, Arial, Helvetica, sans-serif;
        font-size: 14px;
        line-height: 21px;
        box-sizing: border-box;
    }

    .style-38 {
        margin-top: -10px;
        margin-left: -10px;
        margin-right: -10px;
        display: flex;
        flex-wrap: wrap;
        box-sizing: border-box;
    }

    .style-39 {
        padding: 10px;
        flex: 0 0 100%;
        max-width: 100%;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-40 {
        padding-top: 6.25px;
        padding-bottom: 6.25px;
        margin-bottom: 0px;
        font-size: 14px;
        line-height: 21px;
        display: inline-block;
        box-sizing: border-box;
    }

    .style-41 {
        color: rgb(40, 167, 69);
        font-weight: 700;
        box-sizing: border-box;
    }

    .style-42 {
        margin-top: -10px;
        margin-left: -10px;
        margin-right: -10px;
        display: flex;
        flex-wrap: wrap;
        box-sizing: border-box;
    }

    .style-43 {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
        padding: 10px;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-44 {
        font-weight: 700;
        padding-top: 6.25px;
        padding-bottom: 6.25px;
        margin-bottom: 0px;
        font-size: 14px;
        line-height: 21px;
        display: inline-block;
        box-sizing: border-box;
    }

    .style-45 {
        flex: 0 0 66.6667%;
        max-width: 66.6667%;
        padding: 10px;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-46 {
        height: 33px;
        line-height: 33px;
        padding-top: 0px;
        padding-bottom: 0px;
        border-radius: 3px;
        color: rgb(17, 17, 17);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        display: block;
        width: 100%;
        padding: 0px 10.5px;
        font-size: 14px;
        font-weight: 400;
        background-color: rgb(255, 255, 255);
        -webkit-background-clip: padding-box;
        border: 0.666667px solid rgb(206, 212, 218);
        overflow: clip;
        margin: 0px;
        font-family: Roboto, Arial, Helvetica, sans-serif;
        box-sizing: border-box;
        -webkit-background-clip: padding-box;
    }

    .style-47 {
        transition: 0.3s ease-in-out;
        overflow: clip;
        margin: 0px;
        font-family: Roboto, Arial, Helvetica, sans-serif;
        font-size: 14px;
        line-height: 21px;
        box-sizing: border-box;
    }

    .style-48 {
        font-size: 12px;
        color: rgb(220, 53, 69);
        box-sizing: border-box;
    }

    .style-49 {
        margin-top: -10px;
        margin-left: -10px;
        margin-right: -10px;
        display: flex;
        flex-wrap: wrap;
        box-sizing: border-box;
    }

    .style-50 {
        flex: 0 0 33.3333%;
        max-width: 33.3333%;
        padding: 10px;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-51 {
        font-weight: 700;
        padding-top: 6.25px;
        padding-bottom: 6.25px;
        margin-bottom: 0px;
        font-size: 14px;
        line-height: 21px;
        display: inline-block;
        box-sizing: border-box;
    }

    .style-52 {
        flex: 0 0 66.6667%;
        max-width: 66.6667%;
        padding: 10px;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-53 {
        height: 33px;
        line-height: normal;
        padding-top: 0px;
        padding-bottom: 0px;
        border-radius: 3px;
        color: rgb(17, 17, 17);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        display: block;
        width: 100%;
        padding: 0px 10.5px;
        font-size: 14px;
        font-weight: 400;
        background-color: rgb(255, 255, 255);
        -webkit-background-clip: padding-box;
        border: 0.666667px solid rgb(206, 212, 218);
        overflow-wrap: normal;
        text-transform: none;
        margin: 0px;
        font-family: Roboto, Arial, Helvetica, sans-serif;
        box-sizing: border-box;
        -webkit-background-clip: padding-box;
    }

    .style-54 {
        box-sizing: border-box;
    }

    .style-55 {
        box-sizing: border-box;
    }

    .style-56 {
        margin-top: -10px;
        margin-left: -10px;
        margin-right: -10px;
        display: flex;
        flex-wrap: wrap;
        box-sizing: border-box;
    }

    .style-57 {
        margin-left: 186.656px;
        flex: 0 0 66.6667%;
        max-width: 66.6667%;
        padding: 10px;
        text-align: center;
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-left: 10px;
        box-sizing: border-box;
    }

    .style-58 {
        white-space: nowrap;
        border-radius: 4.2px;
        transition: 0.3s ease-in-out;
        cursor: pointer;
        box-shadow: none;
        outline: rgb(255, 255, 255) none 0px;
        text-decoration: none solid rgb(255, 255, 255);
        width: 100% !important;
        padding: 7px 14px;
        font-size: 17.5px;
        line-height: 26.25px;
        color: rgb(255, 255, 255);
        background-color: rgb(0, 123, 255);
        border-color: rgb(0, 123, 255);
        display: inline-block;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        border: 0.666667px solid rgb(0, 123, 255);
        appearance: button;
        text-transform: none;
        overflow: visible;
        margin: 0px;
        font-family: Roboto, Arial, Helvetica, sans-serif;
        box-sizing: border-box;
    }

    .style-59 {
        font-family: 'Font Awesome 5 Pro';
        font-weight: 900;
        -webkit-font-smoothing: antialiased;
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 17.5px;
        box-sizing: border-box;
    }

    .style-60 {
        margin-top: 0px;
        flex: 0 0 50%;
        max-width: 50%;
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        box-sizing: border-box;
    }

    .style-61 {
        margin-bottom: 3.5px;
        box-sizing: border-box;
    }

    .style-62 {
        font-size: 14px;
        margin-bottom: 5px;
        font-weight: 700;
        color: rgb(35, 35, 35);
        box-sizing: border-box;
    }

    .style-63 {
        display: block;
        width: 100%;
        overflow-x: auto;
        box-sizing: border-box;
    }

    .style-64 {
        border: 0px none rgb(33, 37, 41);
        width: 100%;
        margin-bottom: 14px;
        color: rgb(33, 37, 41);
        border-collapse: collapse;
        box-sizing: border-box;
    }

    .style-65 {
        box-sizing: border-box;
    }

    .style-66 {
        background-color: rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .style-67 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-68 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-69 {
        background-color: rgb(250, 250, 250);
        box-sizing: border-box;
    }

    .style-70 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-71 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-72 {
        background-color: rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .style-73 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-74 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-75 {
        margin-top: 21px;
        box-sizing: border-box;
    }

    .style-76 {
        display: block;
        width: 100%;
        overflow-x: auto;
        box-sizing: border-box;
    }

    .style-77 {
        border: 0px none rgb(33, 37, 41);
        width: 100%;
        margin-bottom: 14px;
        color: rgb(33, 37, 41);
        border-collapse: collapse;
        box-sizing: border-box;
    }

    .style-78 {
        box-sizing: border-box;
    }

    .style-79 {
        background-color: rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .style-80 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-81 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-82 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-83 {
        background-color: rgb(250, 250, 250);
        box-sizing: border-box;
    }

    .style-84 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-85 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-86 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-87 {
        background-color: rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .style-88 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-89 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-90 {
        padding: 7px;
        font-size: 13.5px;
        vertical-align: middle;
        text-align: center;
        border: 0.666667px solid rgb(222, 226, 230);
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-91 {
        margin-top: 21px;
        box-sizing: border-box;
    }

    .style-92 {
        margin-bottom: 14px;
        box-sizing: border-box;
    }

    .style-93 {
        font-size: 18px;
        margin-bottom: 5px;
        font-weight: 700;
        color: rgb(35, 35, 35);
        box-sizing: border-box;
    }

    .style-94 {
        display: block;
        width: 100%;
        overflow-x: auto;
        box-sizing: border-box;
    }

    .style-95 {
        border: 0px none rgb(33, 37, 41);
        width: 100%;
        margin-bottom: 14px;
        color: rgb(33, 37, 41);
        border-collapse: collapse;
        box-sizing: border-box;
    }

    .style-96 {
        box-sizing: border-box;
    }

    .style-97 {
        box-sizing: border-box;
    }

    .style-98 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-99 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        text-align: center;
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-100 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        text-align: center;
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-101 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-102 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        border-top: 0.666667px solid rgb(222, 226, 230);
        text-align: left;
        box-sizing: border-box;
    }

    .style-103 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        text-align: center;
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-104 {
        border-bottom-width: 0.666667px;
        white-space: nowrap;
        padding: 7px;
        font-size: 13.5px;
        vertical-align: bottom;
        text-align: center;
        border-top: 0.666667px solid rgb(222, 226, 230);
        box-sizing: border-box;
    }

    .style-105 {
        box-sizing: border-box;
    }
</style>
<div class="style-0">
    <div class="style-1">

        <div class="style-2">
            <p class="style-3"><span class="style-4"><strong class="style-5">chữ in đậm</strong></span></p>
         
            <p class="style-27"><span class="style-28"><em class="style-29">chữ nghiêng</em></span></p>
        </div>
        <div class="style-30">
            <div class="style-31">

                <div class="style-32">
                    <div class="style-33">
                        <div class="style-34">
                            Tạo yêu cầu nạp quỹ
                        </div>
                    </div>
                    <div class="style-35">
                            <form class="style-36" action="billnaptien.php" method="GET">

                            <div class="style-38">
                                <div class="style-39">
                                    <label for="" class="style-40">
                                        Số dư quỹ: <b class="style-41">0 VND</b> </label>
                                </div>
                            </div>
                            <div class="style-42">
                                <div class="style-43">
                                    <label for="" class="style-44">
                                        Số tiền nạp:
                                    </label>
                                </div>
                             <div class="style-45">
    <input name="sotien" id="sotien" type="text" class="style-46" placeholder="Số tiền nạp" value="" />
    <span class="style-48"> Tối thiểu 10,000 VND , Tối đa 1,000,000 VND </span>
</div>

<script>
    document.getElementById('sotien').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');  // Loại bỏ tất cả ký tự không phải số
        if (value) {
            e.target.value = parseInt(value).toLocaleString('vi-VN');  // Thêm dấu phẩy
        }
    });
</script>
</div>

                            <div class="style-49">
                                <div class="style-50">
                                    <label for="" class="style-51">
                                        Cổng thanh toán:
                                    </label>
                                </div>
                                <div class="style-52">
                                    <select class="style-53"  required="">
                                  
                                        <option class="style-55"><?php echo $thongtinbank['nganhang']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="style-56">
                                <div class="style-57">
                                    <button type="submit" class="style-58">
                                        <i class="style-59"></i>
                                        Nạp tiền ngay
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="style-60">
                    <div class="style-61">
                        <div class="style-62">
                            Hạn mức và phí:
                        </div>
                    </div>
                    <div class="style-63">
                        <table class="style-64">
                            <tbody class="style-65">
                                <tr class="style-66">
                                    <td class="style-67">Tổng hạn mức ngày</td>
                                    <th class="style-68">2,000,000 VND</th>
                                </tr>
                                <tr class="style-69">
                                    <td class="style-70">Số tiền tối thiểu</td>
                                    <th class="style-71">10,000 VND</th>
                                </tr>
                                <tr class="style-72">
                                    <td class="style-73">Số tiền tối đa</td>
                                    <th class="style-74">1,000,000 VND</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="style-75">
                        <div class="style-76">
                            <table class="style-77">
                                <tbody class="style-78">
                                    <tr class="style-79">
                                        <th class="style-80">Cổng thanh toán</th>
                                        <th class="style-81">Phí cố định</th>
                                        <th class="style-82">Phí %</th>
                                    </tr>
                                    <tr class="style-83">
                                        <td class="style-84">MB - NH TMCP QUAN DOI</td>
                                        <td class="style-85">0</td>
                                        <td class="style-86">0
                                            %
                                        </td>
                                    </tr>
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="style-91">
            <div class="style-92">
                <div class="style-93">
                    Lịch sử nạp tiền
                </div>
            </div>
            <div class="style-94">
                <table class="style-95">
                    <thead class="style-96">
                        <tr class="style-97">
                            <th class="style-98">Mã đơn</th>
                            <th class="style-99">Nạp vào quỹ</th>
                            <th class="style-100">Số tiền</th>
                            <th class="style-101">Cổng thanh toán</th>
                            <th class="style-102">Ngày tạo</th>
                            <th class="style-103">Trạng thái</th>
                            <th class="style-104">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="style-105">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>