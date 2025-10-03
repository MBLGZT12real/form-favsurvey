$(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
    // Kita sembunyikan dulu untuk loadingnya
    $("#loading").hide();
    
    $("#province").change(function(){ // Ketika user mengganti atau memilih data provinsi
    $("#city").hide(); // Sembunyikan dulu combobox kota nya
    $("#loading").show(); // Tampilkan loadingnya
    
        $.ajax({
            type: "POST", // Method pengiriman data bisa dengan GET atau POST
            url: "load_city.php", // Isi dengan url/path file php yang dituju
            data: {province : $("#province").val()}, // data yang akan dikirim ke file yang dituju
            dataType: "json",
            beforeSend: function(e) {
                if(e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            
            success: function(response){ // Ketika proses pengiriman berhasil
                $("#loading").hide(); // Sembunyikan loadingnya
                
                // set isi dari combobox kota
                // lalu munculkan kembali combobox kotanya
                $("#city").html(response.data_kota).show();
            },
            
            error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(thrownError); // Munculkan alert error
            }
        });
    });
});