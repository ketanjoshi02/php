<?php include 'config.php';
session_start();
?>

   <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-plus fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                            
                        </div>
                        <div>NEW</div>
                    </div>
                </div>
            </div>
            <a href="#" onclick="addDBA();">
                <div class="panel-footer">
                    <span class="pull-left">
                         ADD
                    </span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                         <i class="fa fa-edit fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">
                            
                        </div>
                        <div>Edit</div>
                    </div>
                </div>
            </div>
            <a href="#" onclick="dbaList();breadeditdba();">
                <div class="panel-footer">
                    <span class="pull-left">
                          EDIT
                    </span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>

   