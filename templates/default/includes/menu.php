<div class="container">
    <div class="resume">
        <header class="page-header">
            <h1 class="page-title">Welcome!</h1>
            <small> <i class="glyphicon glyphicon-info-sign"></i> This is a small game, created for people who are very bored. Created by Nemanja M.</small>
        </header>

        <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php showMessage(); ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading resume-heading">
