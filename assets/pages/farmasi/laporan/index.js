
/*menyembunyikan form tanggal, bulan dan tahun saat halaman di load */
$(document).ready(function() {

    $("#tanggalfilter").hide();
    $("#tahunfilter").hide();
    $("#bulanfilter").hide();
    
    $("#tanggalfilter_beli_kredit").hide();
    $("#tahunfilter_beli_kredit").hide();
    $("#bulanfilter_beli_kredit").hide();

    $("#tanggalfilter_beli_tunai").hide();
    $("#tahunfilter_beli_tunai").hide();
    $("#bulanfilter_beli_tunai").hide();
    
    $("#tanggalfilter_penjualan").hide();
    $("#tahunfilter_penjualan").hide();
    $("#bulanfilter_penjualan").hide();

    $("#tanggalfilter_jual_kredit").hide();
    $("#tahunfilter_jual_kredit").hide();
    $("#bulanfilter_jual_kredit").hide();
    
    $("#tanggalfilter_jual_tunai").hide();
    $("#tahunfilter_jual_tunai").hide();
    $("#bulanfilter_jual_tunai").hide();

    
    $("#tanggalfilter_obat_expired").hide();
    $("#tahunfilter_obat_expired").hide();
    $("#bulanfilter_obat_expired").hide();
    
    $("#tanggalfilter_obat_masuk").hide();
    $("#tahunfilter_obat_masuk").hide();
    $("#bulanfilter_obat_masuk").hide();

    $("#tanggalfilter_obat_keluar").hide();
    $("#tahunfilter_obat_keluar").hide();
    $("#bulanfilter_obat_keluar").hide();

    $("#tanggalfilter_obat_stok").hide();
    $("#tahunfilter_obat_stok").hide();
    $("#bulanfilter_obat_stok").hide();
    
    $("#tanggalfilter_hutang").hide();
    $("#tahunfilter_hutang").hide();
    $("#bulanfilter_hutang").hide();
    
    $("#tanggalfilter_piutang").hide();
    $("#tahunfilter_piutang").hide();
    $("#bulanfilter_piutang").hide();
    
      
    var $select1 = $( '#select1' ),
    $pilih_laporan = $( '#pilih_laporan' ),
    $options = $pilih_laporan.find( 'option' );
    $(this).data('options', $('#pilih_laporan option').clone());

    $select1.on( 'change', function() {
    $pilih_laporan.html( $options.filter( '[value="' + this.value + '"]' ) );
    } ).trigger( 'change' );

    $('.select_in_periode').select2({
        minimumResultsForSearch: -1,
    })
        
    $('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yy-mm-dd',
        reverseYearRange: true,
    })
})

$('#select1').select2({
    minimumResultsForSearch: -1,
})
$('#pilih_laporan').select2({
    minimumResultsForSearch: -1,
})
$('#periode').select2({
    minimumResultsForSearch: -1,
    placeholder: "Pilih",
})


/*menampilkan form tanggal, bulan dan tahun*/

function prosesPeriode(){
    var periode = $("[name='periode']").val();

    //not null
    $pilih_laporan = $( "#pilih_laporan option:selected" ).text();
    if($pilih_laporan == "Pembelian"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter").show();
        }
    }else if($pilih_laporan == "Pembelian Kredit"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_beli_kredit").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_beli_kredit").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_beli_kredit").show();
        }
    }else if($pilih_laporan == "Pembelian Tunai"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_beli_tunai").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_beli_tunai").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_beli_tunai").show();
        }
    }else if($pilih_laporan == "Penjualan"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_penjualan").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_penjualan").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_penjualan").show();
        }
    }else if($pilih_laporan == "Penjualan Kredit"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_jual_kredit").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_jual_kredit").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_jual_kredit").show();
        }
    }else if($pilih_laporan == "Penjualan Tunai"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_jual_tunai").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_jual_tunai").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_jual_tunai").show();
        }
    }else if($pilih_laporan == "Obat Expired"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_obat_expired").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_obat_expired").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_obat_expired").show();
        }
    }else if($pilih_laporan == "Obat Masuk"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_obat_masuk").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_obat_masuk").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_obat_masuk").show();
        }
    }else if($pilih_laporan == "Obat Keluar"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_obat_keluar").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_obat_keluar").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_obat_keluar").show();
        }
    }else if($pilih_laporan == "Obat Stok Habis"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_obat_stok").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_obat_stok").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_obat_stok").show();
        }
    }else if($pilih_laporan == "Hutang"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_hutang").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_hutang").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_hutang").show();
        }
    }else if($pilih_laporan == "Piutang"){
        if(periode == "tanggal"){
            $("#btnproses").hide();
            $("#tanggalfilter_piutang").show();
            $("[name='valnilai']").val('tanggal');

        }else if(periode == "bulan"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('bulan');
            $("#bulanfilter_piutang").show();

        }else if(periode == "tahun"){
            $("#btnproses").hide();
            $("[name='valnilai']").val('tahun');
            $("#tahunfilter_piutang").show();
        }
    }else{
    alert("Not Yet")
}
}

/*menytembunyikan form tanggal, bulan dan tahun*/

function prosesReset(){
    $("#btnproses").show();

    $("#tanggalfilter").hide();
    $("#tahunfilter").hide();
    $("#bulanfilter").hide();
    
    $("#tanggalfilter_beli_kredit").hide();
    $("#tahunfilter_beli_kredit").hide();
    $("#bulanfilter_beli_kredit").hide();
    
    $("#tanggalfilter_beli_tunai").hide();
    $("#tahunfilter_beli_tunai").hide();
    $("#bulanfilter_beli_tunai").hide();
    
    $("#tanggalfilter_penjualan").hide();
    $("#tahunfilter_penjualan").hide();
    $("#bulanfilter_penjualan").hide();
    
    $("#tanggalfilter_jual_kredit").hide();
    $("#tahunfilter_jual_kredit").hide();
    $("#bulanfilter_jual_kredit").hide();
    
    $("#tanggalfilter_jual_tunai").hide();
    $("#tahunfilter_jual_tunai").hide();
    $("#bulanfilter_jual_tunai").hide();

    $("#tanggalfilter_obat_expired").hide();
    $("#tahunfilter_obat_expired").hide();
    $("#bulanfilter_obat_expired").hide();
    
    $("#tanggalfilter_obat_masuk").hide();
    $("#tahunfilter_obat_masuk").hide();
    $("#bulanfilter_obat_masuk").hide();
    
    $("#tanggalfilter_obat_keluar").hide();
    $("#tahunfilter_obat_keluar").hide();
    $("#bulanfilter_obat_keluar").hide();
    
    $("#tanggalfilter_obat_stok").hide();
    $("#tahunfilter_obat_stok").hide();
    $("#bulanfilter_obat_stok").hide();
    
    
    $("#tanggalfilter_hutang").hide();
    $("#tahunfilter_hutang").hide();
    $("#bulanfilter_hutang").hide();
    
    $("#tanggalfilter_piutang").hide();
    $("#tahunfilter_piutang").hide();
    $("#bulanfilter_piutang").hide();
    
    
    // $("#periode").val('');
    // $("#tanggalawal").val('');
    // $("#tanggalakhir").val('');
    // $("#tahun1").val('');
    // $("#bulanawal").val('');
    // $("#bulanakhir").val('');
    // $("#tahun2").val('');
}
