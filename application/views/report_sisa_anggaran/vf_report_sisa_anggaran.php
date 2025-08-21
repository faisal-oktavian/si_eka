<div class="form-top-filter form-top-filter">
    <div class="azcrud-container-show-hide azcrud-container-show-hide">
        <div class="azcrud-show-hide-filter"><i class="fa fa-search"></i> Show/Hide Filter</div>
        <div class="form-top-filter-hide form-top-filter-hide">
            <i class="fa fa-chevron-circle-down"></i>
        </div>
    </div>

    <div class="form-top-filter-body form-top-filter-body">
        <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal" id="form_report" target="_blank" method="POST">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3">Tahun</label>
                        <div class="col-md-8 col-sm-6">
                            <div class="container-date">
                                <div class="cd-list">
                                    <div class="input-group az-datetime">
                                        <input type="text" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?php echo date("Y"); ?>">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <button class="btn btn-info btn-filter-evaluasi" id="btn_top_filter" type="button"><i class="fa fa-search"></i> &nbsp;Filter</button>
            <!-- <button class="btn btn-print-neraca btn-default"><i class="fa fa-print"></i> Cetak</button> -->
        </div>
    </div>
</div>