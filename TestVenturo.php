<?php 
if(isset($_GET['tahun']) && $_GET['tahun'] != ""){
    $menu= json_decode(file_get_contents("http://tes-web.landa.id/intermediate/menu"), true);
    $transaksi= json_decode(file_get_contents("http://tes-web.landa.id/intermediate/transaksi?tahun=".$_GET['tahun']), true);
    $nilai = array();
    for ($i = 1; $i <= 12; $i++) {
        $nilai[] = 0;
    }
    foreach ($menu as $key => $pesanan) {
        $menu[$key]['pesanan'] = $nilai;
        $menu[$key]['total'] = 0;
    }
    foreach ($transaksi as $key => $pesanan) {
        $totaltransaksi = $pesanan;
        $hargamakanan = $pesanan['total'];
        $tanggal = DateTime::createFromFormat("Y-m-d", $pesanan['tanggal']);
        $bulan = $tanggal->format("n");
        
        foreach($menu as $key => $pesanan){
            $totalSemua = 0;
            if ($pesanan['menu'] === $totaltransaksi['menu']) {
                $menu[$key]['pesanan'][$bulan - 1] += $totaltransaksi['total'];
                $totalSemua += $totaltransaksi['total'];
            }
            $menu[$key]['total'] += $totalSemua;
        }
    }
    $totalSemuaItem = 0;
    foreach ($menu as $key => $pesanan) {
        $totalSemuaItem += $menu[$key]['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        td,
        th {
            font-size: 11px;
        }
    </style>
    <title>TES - Venturo Camp Tahap 2</title>
</head>
<body>
    <div class="container-fluid">
    <div class="card" style="margin: 2rem 0rem;">
        <div class="card-header">
            Venturo - Laporan penjualan tahunan per menu
        </div>
        <div class="card-body">
            <!-- Mengirim data ke server menggunakan metode HTTP-->
            <form action="" method="get">
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <select id="my-select" class="form-control" name="tahun">
                                <option pesanan="">Pilih Tahun</option>
                                <option pesanan="2021" selected="">2021</option>
                                <!-- Revisi -->
                                <?php
                                    if (isset($_GET['tahun']) && $_GET['tahun'] == '2022') {
                                        echo '<option pesanan="2022" selected>2022</option>';
                                    } else {
                                        echo '<option pesanan="2022">2022</option>';
                                    }
                                ?>
                                <!-- End -->
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary">
                            Tampilkan
                        </button>
                        <a href="http://tes-web.landa.id/intermediate/menu" target="_blank" rel="Array Menu" class="btn btn-secondary">
                            Json Menu
                        </a>
                        <a href="http://tes-web.landa.id/intermediate/transaksi?tahun=2021" target="_blank" rel="Array Transaksi" class="btn btn-secondary">
                            Json Transaksi
                        </a>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" style="margin: 0;">
                    <thead>
                        <tr class="table-dark">
                            <th rowspan="2" style="text-align:center;vertical-align: middle;width: 250px;">Menu</th>
                            <th colspan="12" style="text-align: center;">Periode Pada <?= $_GET['tahun']?></th>
                            <th rowspan="2" style="text-align:center;vertical-align: middle;width:75px">Total</th>
                        </tr>
                        <tr class="table-dark">
                            <th style="text-align: center;width: 75px;">Jan</th>
                            <th style="text-align: center;width: 75px;">Feb</th>
                            <th style="text-align: center;width: 75px;">Mar</th>
                            <th style="text-align: center;width: 75px;">Apr</th>
                            <th style="text-align: center;width: 75px;">Mei</th>
                            <th style="text-align: center;width: 75px;">Jun</th>
                            <th style="text-align: center;width: 75px;">Jul</th>
                            <th style="text-align: center;width: 75px;">Ags</th>
                            <th style="text-align: center;width: 75px;">Sep</th>
                            <th style="text-align: center;width: 75px;">Okt</th>
                            <th style="text-align: center;width: 75px;">Nov</th>
                            <th style="text-align: center;width: 75px;">Des</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (isset($_GET['tahun']) && $_GET['tahun'] != ""): ?>
                        <tr>
                            <td class="table-secondary" colspan="14"><b>Makanan</b></td>
                        </tr>
                    <?php 
                        foreach($menu as $key => $pesanan):
                            if ($pesanan['kategori'] === "makanan"):
                    ?>
                        <tr>
                            <td style="text-align: left;"><?= $menu[$key]['menu'] ?></td>
                            <?php
                            foreach ($pesanan['pesanan'] as $kunci => $nilai):
                            ?>
                                <td style="text-align: right;"><?= $nilai != 0 ?$nilai :""?></td>
                            <?php
                            endforeach;
                            ?>
                            <td style="text-align: right;"><b><?= $pesanan['total'] ?></b></td>
                        </tr>
                        <?php
                            endif;
                        endforeach;
                        ?>
                        <tr>
                            <td class="table-secondary" colspan="14"><b>Minuman</b></td>
                        </tr>
                        <?php 
                        foreach($menu as $key => $pesanan):
                            if ($pesanan['kategori'] === "minuman"):
                        ?>
                        <tr>
                            <td style="text-align: left;"><?= $menu[$key]['menu'] ?></td>
                            <?php
                            foreach ($pesanan['pesanan'] as $kunci => $nilai):
                            ?>
                                <td style="text-align: right;"><?= $nilai != 0 ?$nilai :""?></td>
                            <?php
                            endforeach;
                            ?>
                            <td style="text-align: right;"><b><?= $pesanan['total'] ?></b></td>
                        </tr>
                        <?php
                            endif;
                        endforeach;
                        ?>
                        <tr>
                            <td class="table-dark" colspan="1"><b>Total Harga</b></td>
                            <td class="table-dark" style="text-align: right;" colspan="13"><b><?= $totalSemuaItem ?></b></td>
                        </tr>
                    <?php else: ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row m-3">
                <div class="col-6"><h4>Isi Json Menu</h4><pre style="height: 400px; background:#eaeaea;"><?php var_dump($menu) ?></pre></div>
                <div class="col-6"><h4>Isi Json Transaksi</h4><pre style="height: 400px; background:#eaeaea;"><?php var_dump($transaksi) ?></pre></div>
            </div>
        </div>
    </div>
</body>
</html>