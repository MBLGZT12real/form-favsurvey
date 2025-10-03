<?php
    // Load file koneksi.php
    include ("conn.php");
    
    // Ambil data ID Provinsi yang dikirim via ajax post
    $id_provinsi = $_POST['province'];
    
    // Buat query untuk menampilkan data kota dengan provinsi tertentu (sesuai yang dipilih user pada form)
    $query13="SELECT * FROM sv_wilayah WHERE kode LIKE '$id_provinsi%' AND LENGTH(kode)=5 ORDER BY kode ASC";
    $tampil13=mysqli_query($koneksi,$query13) or die (mysqli_error($koneksi));
    
    // Buat variabel untuk menampung tag-tag option nya
    // Set defaultnya dengan tag option Pilih
    $html = "<option value=''> --- Select City --- </option>";
    while($data13=mysqli_fetch_array($tampil13)){ // Ambil semua data dari hasil eksekusi $sql
      $html .= "<option value='".$data13['nama']."'>".$data13['nama']."</option>"; // Tambahkan tag option ke variabel $html
    }
    
    $callback = array('data_kota'=>$html); // Masukan variabel html tadi ke dalam array $callback dengan index array : data_kota
    echo json_encode($callback); // konversi varibael $callback menjadi JSON
?>