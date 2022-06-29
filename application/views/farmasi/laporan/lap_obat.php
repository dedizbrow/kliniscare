<!DOCTYPE html>
<html>

<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?= (isset($page_title)) ? $page_title : ''; ?></title>
</head>

<body>
    <div class="body-print-pdf">
        <p style="text-align: center"><b>Laporan Obat</b></p>
        <table class="" width="100%" cellpadding="5" cellspacing="0">
            <tr>
                <td width="150px">Periode</td>
                <td width="5%">:</td>
                <td>
                    <?php echo $periode ?>
                </td>
            </tr>
            <tr>
                <td>Jenis Laporan</td>
                <td>:</td>
                <td><?php echo $jenis_laporan ?>
                </td>
            </tr>
        </table>
        <p></p>
        <table class="lap" width="100%" cellpadding="5" cellspacing="0" autosize="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Kategori</th>
                    <th>Satuan Obat</th>
                    <th>Satuan Beli</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Supplier</th>
                    <th>Stok</th>
                    <th>Stok Miniman</th>
                    <th>Expired</th>
                    <th>Isi Persatuan Beli</th>
                    <th>Laba</th>
                    <th>Konversi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($datafilter as $row) : ?> <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row->kode; ?></td>
                        <td><?php echo $row->nama; ?></td>
                        <td><?php echo $row->namaKategoriobat; ?></td>
                        <td><?php echo $row->namaSatuanobat; ?></td>
                        <td><?php echo $row->namaSatuanbeli; ?></td>
                        <td><?php echo number_format($row->hargaBeli); ?></td>
                        <td><?php echo number_format($row->hargaJual); ?></td>
                        <td><?php echo $row->namaSupplier; ?></td>
                        <td><?php echo $row->stok; ?></td>
                        <td><?php echo $row->stokmin; ?></td>
                        <td><?php echo $row->expired; ?></td>
                        <td><?php echo $row->isiperSatuanbeli; ?></td>
                        <td><?php echo $row->laba; ?></td>
                        <td><?php echo $row->konversi; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>


<style>
    body {
        font-family: arial;
        font-size: 8pt;
    }

    .row-title-page {
        margin-bottom: 20px;
    }

    table tr th {
        vertical-align: left;
        /* background-color: #99ffff; */
        background-color: #C0C0C0;
        color: black;
    }

    table tbody tr td {
        vertical-align: left;
        border: 1px groove black;
    }

    .lap {
        vertical-align: top;
        border: 1px solid black;
    }

    table.table-desc {
        line-height: 1.5
    }

    table {
        page-break-inside: auto
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    .page-header-pdf {
        padding-top: 0cm;
        padding-left: 0cm;
        padding-right: 0cm
    }

    .body-print-pdf {
        padding-left: 2cm;
        padding-right: 2cm;
    }

    .page-footer-pdf {
        text-align: center;
        position: relative;
        background-image: url(<?= base_url('assets/img/ym-doc-footer.png'); ?>);
        background-repeat: no-repeat;
        background-size: 100% 100%;
        z-index: 1000;
        margin-top: -33px;
    }

    .page-footer-text {
        color: #fff;
        font-size: 10px;
        font-weight: bold;
        padding: 40px 3px 12px 3px
    }
</style>