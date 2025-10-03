<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<?php include "conn.php"; ?>
<html>
	<head>
		<?php include "head.php"; ?>
		
		<!-- Load librari/plugin jquery nya -->
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <!-- Load File javascript config.js -->
        <script src="js/config.js" type="text/javascript"></script>
	</head>
	<body>
		<div>
			<img class="header" src="images/Header.webp" style="margin:0px;">
		</div>
		<?php 
			if($_SERVER['REQUEST_METHOD']=="POST") { 
			    // Data personal
				$form_nama  = mysqli_real_escape_string($koneksi, strtoupper($_POST['name']));
				$form_phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
				$form_city  = mysqli_real_escape_string($koneksi, strtoupper($_POST['city']));
				$form_prov  = mysqli_real_escape_string($koneksi, strtoupper($_POST['province']));
				$form_date  = mysqli_real_escape_string($koneksi, $_POST['date']);
				$form_time  = mysqli_real_escape_string($koneksi, $_POST['time']);
				$form_email = mysqli_real_escape_string($koneksi, $_POST['email']);

				if (!empty($form_email)) { 
					// Kolom awal
					$columns = ["fullname","phone","email","city","province","date","time"];
					$values  = [
						"'$form_nama'",
						"'$form_phone'",
						"'$form_email'",
						"'$form_city'",
						"'$form_prov'",
						"'$form_date'",
						"'$form_time'"
					];

					// Ambil semua kategori untuk buat cat1, cat2, dst
					$catsql = "SELECT * FROM sv_kategori ORDER BY id_kat ASC";
					$cathsl = $koneksi->query($catsql);

					$counter = 1;
					while($category = $cathsl->fetch_assoc()){ 
						$fieldName = "pilihan" . $category['id_kat']; // input name di form
						$colName   = "cat" . $counter;                // kolom di tabel sv_result

						$columns[] = $colName;

						if(isset($_POST[$fieldName]) && $_POST[$fieldName] !== ""){ 
							$val = mysqli_real_escape_string($koneksi, $_POST[$fieldName]);
							$values[] = "'$val'";
						} else { 
							$values[] = "NULL";
						}
						$counter++;
					}

					// Susun query dinamis
					$sql = "INSERT INTO sv_result (" . implode(",", $columns) . ") 
							VALUES (" . implode(",", $values) . ")";

					// Eksekusi
					$save = mysqli_query($koneksi, $sql) or die(mysqli_error($koneksi));
					
					if ($save) { 
						echo '
							<div class="alert alert-info alert-dismissable" style="font-size:20px;">
								Data saved successfully.
								<script type="text/javascript">
									setTimeout(function(){
										window.location = "thanks.php";
									}, 1000);
								</script>
							</div>
						';
					} else { 
						echo '
							<div class="alert alert-danger alert-dismissable" style="font-size:20px;">
								Data failed to save, please try again.
								<script type="text/javascript">
									var r = alert("Data failed to save.!");
									setTimeout(function(){
										window.location = "index.php";
									}, 1000);
								</script>
							</div>
						';
					}
				} else { 
					echo '
						<script type="text/javascript">
							var r = alert("Data not valid, Data failed to save.!");
							setTimeout(function(){
								window.location = "index.php";
							}, 1000);
						</script>
					';
				}
			}
			
			// Mengambil data kategori
			$catsql = "SELECT * FROM sv_kategori";
			$cathsl = $koneksi->query($catsql);
			
			// Mengambil data pilihan
			$chosql = "SELECT * FROM sv_pilihan WHERE id_kat > '0' ORDER BY RAND()";
			$chohsl = $koneksi->query($chosql);
			
			$choices = [];
			if($chohsl->num_rows > 0){
				while($row = $chohsl->fetch_assoc()){
					$choices[$row['id_kat']][] = $row;
				}
			}
		?>
		
		<form id="regForm" name="regForm" action="" method="post" enctype="multipart/form-data">
			<!-- One "tab" for each step in the form: -->
			<h1>Polling Fav Booth &#38; Vehicles IMOS 2025</h1>
			<div class="tab"> <!--Personal Data-->
				<p class="title">Personal Data:</p>
				<p>
					Full Name:<br/>
					<input type="text" placeholder="Full Name..." oninput="this.className = ''" name="name" maxlength="50" required >
				</p>
				<p>
					No.Phone:<br/>
					<input type="number" placeholder="No Phone..." oninput="this.className = ''" name="phone" maxlength="20" required >
				</p>
				<p>
					Email:<br/>
					<input type="email" placeholder="Email..." oninput="this.className = ''" name="email" id="email" maxlength="50" required >
				</p>
				<p>
					Province:<br/>
					<select name="province" id="province" placeholder="Province..." oninput="this.className = ''" required >
						<option value=""> --- Select Province --- </option>
						<?php
							$query1="SELECT * FROM sv_wilayah WHERE LENGTH(kode)=2 ORDER BY kode ASC";
							$tampil1=mysqli_query($koneksi,$query1) or die(mysqli_error());
							while($data1=mysqli_fetch_array($tampil1)){
						?>
						<option value="<?php echo $data1['kode'];?>"><?php echo $data1['nama'];?></option>
						<?php
							}
						?>
					</select>
				</p>
				<p>
					City:<br/>
					<select name="city" id="city" placeholder="City..." required>
    					<option value=""> --- Select City --- </option>
    				</select>
    				
    				<div id="loading" style="margin-top: 15px;">
                        <img src="images/loading.gif" width="18"> <small>Loading...</small>
                    </div>
				</p>
				<p>
					<input type="hidden" value="<?php echo date("Y-m-d");?>" oninput="this.className = ''" name="date" readonly >
				</p>
				<p>
					<input type="hidden" value="<?php echo date("H:i:s");?>" oninput="this.className = ''" name="time" readonly >
				</p>
			</div>
			
	<?php
		if($cathsl->num_rows > 0){
			while($category = $cathsl->fetch_assoc()){
				echo '<div class="tab">';
					echo '<p class="title">'.$category["id_kat"].'. '.$category["nama"].'</p>';
					
					echo '<div class="myGrid">';
						if(isset($choices[$category["id_kat"]])){
							foreach($choices[$category["id_kat"]] as $option){
							echo '<div class="grid-item">';
								echo '<div class="grid-item-border">';
									echo '<img class="img-select" src="'.$db_file.''.$option["gambar"].'" alt="'.$option["alt"].'" loading="lazy"/>'.$option["nama"];
								echo '</div>';
							echo '</div>';
							}
						}
					echo '</div>';
					
					echo '<p> Pilihan:';
						//echo '<select name="pilihan'.$category["id_kat"].'" required>';
						echo '<select name="pilihan'.$category["id_kat"].'" oninput="this.className()" required >';
							echo '<option value=""> -- Pilihan Anda -- </option>';
							if(isset($choices[$category["id_kat"]])){
								foreach($choices[$category["id_kat"]] as $option){
									echo '<option value="'.$option["nama"].'">'.$option["nama"].'</option>';
								}
							}
						echo '</select>';
					echo '</p>';
				echo '</div>';
			}
		}
	
			// Mengambil data kategori
			$query = "SELECT * FROM sv_kategori";
			$result = $koneksi->query($query);
			echo '<div style="text-align:center;margin-top:40px;margin-bottom:-50px;">';
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					echo '<span class="step"></span>';
				}
				echo '<span class="step"></span>';
			}
			echo '</div>';
	?>
			<br/>
			<div style="overflow:auto;">
				<div style="float:right;">
					<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
					<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
				</div>
			</div>
			
			<div class="modal">
				<span class="close">x</span>
				<img class="modalImage" id="img01">
				<div class="overlay1"></div>
			</div>
		</form>
		
		<div>
			<img class="header" src="images/Footer.webp" style="margin:0px;">
		</div>
		
		<script>
			var modalEle = document.querySelector(".modal");
			var modalImage = document.querySelector(".modalImage");
			var overlay1 = document.querySelector(".overlay1");
			Array.from(document.querySelectorAll(".img-select")).forEach(item => {
			   item.addEventListener("click", event => {
				  modalEle.style.display = "block";
				  modalImage.src = event.target.src;
				  overlay1.innerHTML = event.target.alt;
			   });
			});
			
			document.querySelector(".close").addEventListener("click", () => {
			   modalEle.style.display = "none";
			});
			
			document.getElementById("email").onblur = function(){
				if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(regForm.email.value)){
					return (true);
				}else{
					document.getElementById("email").value="";
					alert("You have entered an invalid email address!");
					return (false);
				}
			};
			
			var currentTab = 0;
			showTab(currentTab);
			
			function showTab(n){
				var x = document.getElementsByClassName("tab");
				x[n].style.display = "block";
				
				if(n == 0){
					document.getElementById("prevBtn").style.display = "none";
				}else{
					document.getElementById("prevBtn").style.display = "inline";
				}
				
				if(n == (x.length - 1)){
					document.getElementById("nextBtn").innerHTML = "Submit";
				}else{
					document.getElementById("nextBtn").innerHTML = "Next";
				}
				
				fixStepIndicator(n)
			}
			
			function nextPrev(n){
				var x = document.getElementsByClassName("tab");
				if (n == 1 && !validateForm()) return false;
				x[currentTab].style.display = "none";
				currentTab = currentTab + n;
				if(currentTab >= x.length){
					document.getElementById("regForm").submit();
					return false;
				}
				showTab(currentTab);
			}
			
			function validateForm(){
				var x, y, i, valid = true;
				x = document.getElementsByClassName("tab");
				y = x[currentTab].getElementsByTagName("input");
				z = x[currentTab].getElementsByTagName("select");
				for(i = 0; i < y.length; i++){
					if(y[i].value == ""){
						y[i].className += " invalid";
						valid = false;
					}
				}
				
				for(i = 0; i < z.length; i++){
					if(z[i].value == ""){
						z[i].className += " invalid";
						valid = false;
					}
				}
				
				if(valid){
					document.getElementsByClassName("step")[currentTab].className += " finish";
				}
				return valid;
			}
			
			function fixStepIndicator(n){
				var i, x = document.getElementsByClassName("step");
				for(i = 0; i < x.length; i++){
					x[i].className = x[i].className.replace(" active", "");
				}
				x[n].className += " active";
			}
		</script>
	</body>
</html>