<style>
    thead > tr {
        background-color: #144e7c;
    }
    thead > tr > th {
        color: #ffffff;
        text-align: center;
        font-size: 14px;
        vertical-align: middle !important;
    }  
    .modal-lg {
        width:auto !important;
    }
    .table-responsive{
        font-size: 12px; 
        margin-top: 0px;
        overflow-x: auto !important;
        position: relative;
        width: 100%;
        /* min-height: 400px; jika perlu */
    }
    table {
        border-collapse: separate !important;
        /* Agar sticky tidak bug di beberapa browser */
    }
    /* Tambahan: atur lebar kolom */
    th.col-uraian, td.col-uraian { width: 220px; min-width: 220px; }
    th.col-vol, td.col-vol { width: 60px; min-width: 60px; }
    th.col-rp, td.col-rp { width: 100px; min-width: 100px; }
    th.col-tanggal, td.col-tanggal { width: 110px; min-width: 110px; }
    th.col-lk, td.col-lk,
    th.col-pr, td.col-pr { width: 45px; min-width: 45px; }
    th.col-harga, td.col-harga { width: 100px; min-width: 100px; }
    th.col-ppn, td.col-ppn,
    th.col-pph, td.col-pph { width: 100px; min-width: 100px; }
    th.col-total, td.col-total { width: 100px; min-width: 100px; }
    th.col-gender, td.col-gender { width: 90px; min-width: 90px; }
    th.col-persentase, td.col-persentase { width: 100px; min-width: 100px; }
    th.col-sisa-vol, td.col-sisa-vol { width: 60px; min-width: 60px; }
    th.col-sisa-rp, td.col-sisa-rp { width: 100px; min-width: 100px; }

    /* Border kanan untuk kolom terakhir di thead dan tbody */
    th:last-child, td:last-child {
        border-right: 1px solid #dee2e6 !important;
    }

    /* Freeze kolom uraian */
    th.col-uraian, td.col-uraian {
        position: sticky !important;
        left: 0;
        background: #fff;
        z-index: 2;
        box-shadow: 2px 0 2px -1px #ccc;
    }
    th.col-uraian {
        z-index: 3; /* Supaya header di atas cell */
    }

    th.col-uraian-title {
        background: #144e7c !important;
        color: #fff !important;
    }
</style>

<div class="table-responsive">
    <div class="detail-table"></div>
</div>