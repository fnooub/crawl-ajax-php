<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="mess">
            
        </div>
        
        <script>
            
            function showMess(mess){
                $('#mess').append("<p>" + mess + "</p>");
            }
            
            // Danh sách trang cần lấy
            var listChar = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y"];
            
            // Vị trí hiện tại của trang ký tự
            var currentChar = 0;

            // Trang hiện tại cần lấy
            var currentPage = 1;

            // Lưu tất cả các bài hát của 1 trang
            var allItem = [];

            function startAPage()
            {
                if (currentPage == 0){
                    currentPage = 1;
                    currentChar++;
                }
                allItem = [];
                savePageIndex = 0;
                $.ajax({
                    url : "/test/getpage.php",
                    data : {
                        url : "http://chonbaihat.com/bat-dau-bang/" + listChar[currentChar] + '/trang/' + currentPage
                    },
                    type : "get",
                    dataType:"text",
                    success : function(result){
                        showMess("<span style='color: red'>Tải trang: http://chonbaihat.com/bat-dau-bang/" + listChar[currentChar] + '/trang/' + currentPage + "</span>");
                        
                        var html = $.parseHTML(result);
                        var items = $(html).find(".list-song .song");

                        for (var i = 0; i < items.length; i++)
                        {
                            var html = $.parseHTML(result);
                            var item = $(items[i]).find(".name a").attr('href');
                            allItem.push(item);
                        }
                        
                        // Bắt đầu duyệt lưu từng bài hát
                        if (allItem.length > 0){
                            savePage();
                        }
                        else {
                            showMess('<span style="color: blue">Hết, qua ký tự mới!</span>"');
                            if (currentChar == listChar.length - 1){
                                showMess("Kết thúc!");
                            }
                            else {
                                currentPage = 0;
                                startAPage();
                            }
                            
                        }
                    }
                });
            }

            startAPage();

            var savePageIndex = 0;
            function savePage()
            {
                var url = allItem[savePageIndex];
                $.ajax({
                    url : "/test/getpage.php",
                    data : {
                        url : url
                    },
                    type : "get",
                    dataType:"text",
                    success : function(result)
                    {
                        var html = $.parseHTML(result);

                        var data = {
                            title : $(html).find('.song.detail .name').text(),
                            maso : $(html).find('.song.detail .number').text(),
                            composer : $(html).find('.song.detail .artist a').text(),
                            content : $(html).find('.song.detail .lyric').html()
                        };

                        $.ajax({
                            url : "/test/save.php",
                            data : data,
                            type : "post",
                            dataType:"text",
                            success : function(result){
                                showMess('<span style="color: blue">Lưu thành công bài hát ' + data.title + "</span>");
                                // Qua bài tiếp theo
                                savePageIndex++;
                                if (savePageIndex < allItem.length - 1){
                                    savePage();
                                }
                                else {
                                    currentPage++;
                                    startAPage();
                                }
                            }
                        });
                    }
                });
            }

        </script>
    </body>
</html>
