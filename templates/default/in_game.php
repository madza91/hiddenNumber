<div class="row">
    <div class="col-lg-12">
        <h1>Guess the number!</h1>
        <p><?php showMessage(); ?></p>
        <form method="post">
            <div class="form-group">
                <input type="text" name="new_guess" min="-10000" max="10000" class="form-control" autofocus>
                <small id="emailHelp" class="form-text text-muted"> Select a number between -10.000 & 10.000 and click
                    '<i>Try this number!</i>' button.
                </small>
            </div>
            <div class="text-center">
                <button type="submit" name="do_guess" class="btn btn-success">Try this number!</button>
            </div>
        </form>
        <hr>
        <h1>Guess the secret number!</h1>
        <div class="alert alert-info" role="alert">
            This is very small and simple game. When you start new game, we create our secret number between
            <b>-10.000</b> and <b>10.000</b>.
            You goal is to guess the secret number.
        </div>
        <hr>
        <form method="post">
            <div class="text-center">
                <h5>Get bored? Close game and get your secret number.</h5>
                <button type="submit" name="close_game" class="btn btn-danger">Close game</button>
            </div>
        </form>
    </div>
</div>
