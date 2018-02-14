<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">

</head>
<body>
<div class="container">
    <h1>Наша гостевая книга</h1>
    <div class="row">
        <div class="col-md-12" id="comments"></div>
    </div>
    <div class="row">
        <form class="form-horizontal" id="form-comment">
        <input type="hidden" name="action" value="addcomment"/>
        <fieldset>
            <legend>Написать нам</legend>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Ваше имя</label>
                <div class="col-sm-10">
                    <input type="text" name="name" value="" id="input-name" class="form-control">
                </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-enquiry">Ваш вопрос или сообщение</label>
                <div class="col-sm-10">
                    <textarea name="comment" rows="10" id="input-enquiry" class="form-control"></textarea>
                </div>
            </div>
            <input type="hidden" name="id" value=""/>
        </fieldset>
        <div class="buttons">
            <div class="pull-right">
                <button class="btn btn-primary" type="submit">Отправить сообщение</button>
            </div>
        </div>
    </form>
    </div>
</div>
</body>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="script/main.js"></script>
</html>