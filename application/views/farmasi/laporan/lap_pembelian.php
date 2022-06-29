<!DOCTYPE html>
<html>

<head>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="utf-8">
    <title><?= (isset($page_title)) ? $page_title : ''; ?></title>
</head>

<body>
    <div class="body-print-pdf">
        <p style="text-align: center"><b>Laporan Pembelian</b></p>
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
                    <th>Faktur</th>
                    <th>Tanggal</th>
                    <th>Jenis bayar</th>
                    <th>Lama Kredit</th>
                    <th>Tempo</th>
                    <th>Supplier</th>
                    <th>Subtotal</th>
                    <th>Diskon</th>
                    <th>Grand Total</th>
                    <th>Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                foreach ($datafilter as $row) : ?> <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row->faktur; ?></td>
                        <td><?php echo $row->tanggal; ?></td>
                        <td><?php echo $row->tunai_kredit; ?></td>
                        <td><?php echo $row->kredit_hari; ?></td>
                        <td><?php echo $row->jatuh_tempo; ?></td>
                        <td><?php echo $row->namaSupplier; ?></td>
                        <td><?php echo number_format($row->subtotal); ?></td>
                        <td><?php echo number_format($row->diskonsub); ?></td>
                        <td><?php echo number_format($row->grandtotal); ?></td>
                        <td><?php echo number_format($row->bayar); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <!-- <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th class="text-center"><?= $total_qty; ?></th>
                    <th></th>
                    <th></th>
                    <th><span>Rp. </span><span class="pull-right"><?= number_format($total_biaya); ?></span></th>
                </tr>
            </tfoot> -->
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

    table tbody tr td {
        vertical-align: left;
        border: 1px groove black;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto
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