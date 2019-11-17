<div class="row">
    <div class="col-lg-12">
        <form method="post">
            <div class="form-group">
                <label for="exampleInputEmail1">Your full name or nickname</label>
                <input type="text" name="username" class="form-control" id="exampleInputEmail1"
                       aria-describedby="emailHelp" placeholder="Your name" minlength="3" value="<?= $username; ?>" autofocus>
                <small id="emailHelp" class="form-text text-muted">This name will be showed in Hi-Score list, if you
                    guess the number.
                </small>
            </div>
            <div class="text-center">
                <button type="submit" name="new_game" class="btn btn-success">Let's play!</button>
            </div>
        </form>
        <hr>
        <h1>Guess the secret number!</h1>
        <div class="alert alert-info" role="alert">
            This is very small and simple game. When you start new game, we create our secret number between
            <b>-10.000</b> and <b>10.000</b>.
            You goal is to guess the secret number.
            This game is played <b><?= $totalPlayed; ?></b> times.
        </div>
        <hr>
    </div>
</div>
</div>
<div class="bs-callout bs-callout-danger">
    <h4>Hi-Score</h4>
    <table class="table table-striped table-responsive ">
        <thead>
        <tr>
            <th>Place</th>
            <th>Username</th>
            <th>Attempts</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($score as $key => $user) : ?>
            <tr>
                <td><?= ($key + 1); ?>.</td>
                <td><?= $user->username; ?></td>
                <td><?= $user->guess_total; ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
