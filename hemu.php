<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>udemy cookies.txt Update Portal</title>
</head>
<body>
    <?php
if(isset($_POST['done'])){
$myfile = fopen("cookiehegwew.txt", "w") or die("Unable to open file!");
$txt = "#HttpOnly_.semrush.com	TRUE	/	TRUE	0	PHPSESSID	".$_POST['post']."\n#HttpOnly_.semrush.com	TRUE	/	TRUE	0	sso_token	".$_POST['post1']."\n";

if(fwrite($myfile, $txt)){
    $done = "<p style='color:green;font-size:20px;'>Updated...</p>";
}else{
    $done = "<p style='color:red;'>Not Updated...</p>";
}
fclose($myfile);

}
?>

    <form action="" method="post">
        <div class="input-type">
            <center>
                <h2><span style="color: green;">udemy user cookies.txt</span> Update Portal</h2>
                <?php if(isset($_POST['done'])){echo $done;} ?>
                    <textarea rows="30" cols="50" name="post"></textarea>
                    <br>
                    <input type="submit" value="update" name="done"  />
            </center>
        </div>
    </form>
    <style>
        body{
            border: 4px solid darkorange;
            height: 80%;
            padding: 25px 25px;
            margin: 25px 25px;
            box-shadow: 0 0 20px 1px rgb(193, 192, 192);
            border-radius:25px;
            user-select: none;
        }
        input{
            font-size: 30px;
        }
        input[type="text"] {
            border: 2px solid darkorange;
            background: white;
            outline: none;
            color: darkorange;
            padding: 10px 15px;
            width: 45vw;
            border-radius: 20px;
            margin: 15px 0px;

        }
        input[type="submit"] {
            border: 2px solid darkorange;
            background: darkorange;
            color: white;
            padding: 10px 25px;
            width: 45vw;
            border-radius: 20px;
            margin: 15px 0px;
            outline: none;
        }
        input::placeholder{
            color:darkorange;
            font-weight:bold;
        }
        h2{
            color: darkorange;
            font-weight: bold;
            padding: 10px 0;
            width: 45vw;
            box-shadow: 0 0 20px 1px rgb(213, 213, 213);
            border-radius: 50px;
        }
        ::selection {
            color: darkorange;

        }
    </style>

</body>
</html>