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
				$('#mess').prepend("<p>" + mess + "</p>");
			}

			function saveEbook() {

				$.ajax({
					url : "savebook_bns.php",
					data : { dulieu : txt },
					type : "post",
					dataType : "html"
				}).done(function(dulieu){
					showMess('<span style="color: green">Lưu thành công</span>');
				});

			}
			
			function getContent() {
				chapId = chapList[count];

				$.ajax({
					url : "getpage_bns.php",
					data : { link : 'https://bachngocsach.com' + chapId },
					type : "get",
					dataType : "text"
				})
				.done(function(dulieu) {
					var $dulieu = $(dulieu);

					var noidung = $dulieu.find('#noi-dung').html();
					var tieude = $dulieu.find('h1').text().trim();

					txt += '<h1>' + tieude + '</h1>';
					txt += noidung;

					showMess('<span style="color: blue">' + tieude + '</span>');

					count++;

					if (count >= chapListSize) {
						saveEbook();
					} else {
						getContent();
					}
				})
				.fail(function(loi){
					console.log(loi);
				});
			}

			var count = 0,
				chapList = [],
				chapListSize = 0,
				txt = '',
				urlBook = 'https://bachngocsach.com/reader/ban-tien-convert/muc-luc',
				chapId = '';

			// get ds link chuong
			$.ajax({
				url : "getpage_bns.php",
				data : { link : urlBook },
				type : "get",
				dataType : "text"
			}).done(function(dulieu){
				var $dulieu = $($.parseHTML(dulieu));

				var $chapList = $dulieu.find('#mucluc-list a');
				if ($chapList.length) {
					$chapList.each(function() {
						chapList.push($(this).attr('href'));
					});
				}
				console.log(chapList);

				chapListSize = chapList.length;
				if (chapListSize > 0) {
					getContent();
				}

			}).fail(function(loi){
				console.log(loi);
			});

		</script>
	</body>
</html>
