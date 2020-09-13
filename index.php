<html>
	<head>
		<title>간이 망연계 - copy &amp; paste</title>
		<script>
			function setAction(action_name){
				document.forms["form1"]["action"].value = actio_name;
			}

			function validateForm() {
				var input_value = document.forms["form1"]["input_value"].value;
				if (input_value == "") {
					alert("Empty input. Put something there.");
					return false;
				}
			}

			function resetForm(){
<!--
				alert('called');
				alert('Before: input_value: ' + document.form1.input_value.value);
				alert('After : input_value: ' + document.form1.input_value.value);
				document.form1.output_value1.value = "";
-->
				document.getElementById("form1").reset();

				document.getElementById("input_value").value = "6";
				document.getElementById("output_value1").value = "";
			}

			function copyOutput(){

				const copyText = document.createElement("textarea");
				copyText.value = document.getElementById("output_value1").value;

				document.body.appendChild(copyText);

				try {
					copyText.select();
				} catch {};

				try {
					copyText.setSelectionRange(0,99999); /* for mobile device */
				} catch {};

				document.execCommand("copy");

				alert('copied text: ' + copyText.value);

				document.body.removeChild(copyText);
			}

			function copyOutput1(){
				var copyText = document.querySelector("#input");

				try {
					copyText.select();
				} catch(err) {;} 

				document.execCommand("copy");
			}

//			document.addEventListener("click", copyOutput);

	</script>
	</head>
	<body>

<?php
$base="/Users/k2dx9a/cp";
$today=date("Y/m/d");
$dir="$base/$today";


if (!is_dir($dir)) {
	if (!mkdir($dir, 0755, TRUE)) {
		die("Unable to access directory: $dir");
	}
}

$index="$dir/index.dat";
$log="$dir/access.log";

// copy icon
// $copy_icon="<img src=\"copy.png\" alt="copy to clipboard" style="width:20px;height:20px;\"></a>";


$input_value="";
$output_value="";
$output_error="";
$display_copy_icon=false;
$copy_icon="";

$action_request=$_REQUEST['action'];
$input_value=$_REQUEST['input_value'];

if (is_null($input_value)) {
	if(!is_null($action_request)) {
		$output_error="Error. 입력값 없음";
	}
} else {

	switch ($_REQUEST['action']) {
		case "register":
			// $index
			if(!is_file($index)) {
				// make new index file
				$file_no = 0;
				file_put_contents($index, $file_no);
			} else {
				$file_no = file_get_contents($index);
				$file_no = $file_no + 1;
				file_put_contents($index, $file_no);
			}

			$target_file = $file_no . ".txt";
			$target_path = "$dir/$target_file";

			if(is_writable($dir)) {
				if(file_put_contents($target_path, $input_value)) {
					$output_value = $file_no;
				} else {
					die("Unable to write file - $target_file  (Location 1). IT보안실에 연락바랍니다.");
				}
			} else {
				die("Unable to write file - $target_file (Location 2). IT보안실에 연락바랍니다.");
			}

			break;
		case "search":
			$target_file = $input_value . ".txt";
			$target_path = "$dir/$target_file";

			if(!is_file($target_path)) {
				$output_error="Error. $input_value : 등록내용 없음";
				break;
			} else {
				$output_value = file_get_contents($target_path);
				$display_copy_icon=true;
			}

			break;
		case 'test':
			phpinfo();
			break;
		default:
			$input_value="";
	}
}

?>

		<h1 style="color:White; background-color:DodgerBlue; text-align:center;"> 간이 망연계 - 텍스트 교환</h1>
		<p style="color:White; background-color:Tomato; text-align:center;"> 경고! 개인정보 절대 등록 금지. 보안성 없음. 작업로그 기록됨.</p>

		<p style="small;"> 
			* 최대 256바이트 간편 공유: 텍스트 등록 > 등록 번호 전달 > 등록 번호 조회> 텍스트 추출</br>
		</p>

		&nbsp;</p>

		<form id="form1" name="form1" method="post">
			<input type="hidden" name="action" value="">
    	<p>
				1. 입력   (조회 번호 또는 등록할 텍스트):</br>
				<input type=text id="input_value" style="width:100%; font-family: monospace,monospace;" name="input_value" size=100 maxlength=256 value="<?php echo $input_value; ?>">
			</p>
			<p>
				2. 작업: 
				<input type=submit name="search_button" value="조회" onClick="form1.action.value='search'; return;">
				<input type=submit name="register_button" value="등록" onClick="form1.action.value='register'; return;">
				<input type=submit id="reset_button" name="reset_button" value="초기화" onClick="form1.action.value='none'; return;">



<!--
				<input type=submit name="search_button" value="조회 test" onClick="setAction('test');">
				<input type=reset id="reset_button1" name="reset_button" value="초기화 test" onClick="alert('hehe');">
				<input type=submit id="reset_button2" name="reset_button" value="초기화" onClick="resetForm()">
-->
		</form>

			</p>
			<p>
				3. 출력: 
				<?php if($display_copy_icon) echo "<button onclick=\"copyOutput()\">출력 복사</button>\n"; ?>
				<?php if($display_copy_icon) echo $copy_icon; ?><b style="color:red;"><?php echo $output_error; ?></b></br>
				<input type=text id="output_value1" style="width:100%; font-family: monospace,monospace;" name="output_value" value="<?php echo $output_value; ?>" disabled>
				<!-- hidden variable for text copy -->
				<input type=hidden id="ov1" value="none">
			</p>

		&nbsp;</p>

		<!-- 설명글-->
		<h2> 사용법</h2>
		<p>텍스트 등록 및 공유</p>
		<ol>
			<li> 입력 란에 공유할 텍스트 입력 </li>
			<li> 등록 버튼 클릭</li>
			<li> 출력 란에 표시된 값(등록번호)을 상대방에 전달</li>
		</ol>
		<p>텍스트 이용</p>
		<ol>
			<li> 입력 란에 전달받은 값(등록번호)을 입력</li>
			<li> 조회 버튼 클릭</li>
			<li> 출력 란에 표시된 텍스트를 복사하여 이용</li>
		</ol>

		<h2> 안내</h2>
		<ul>
			<li> 최대 256 바이트 텍스트만 업로드 가능 (파일 불가)</li>
			<li> 번호는 매일 자정 이후 0으로 리셋됨</li>
		</ul>
	</body>
</html>
