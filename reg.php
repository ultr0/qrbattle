<?php
if(!empty($_POST["reg_down"]))
{
    setcookie("TeamQR", 0, time()-3600*24*30*12, "/", "qrbattle.ru");
    unset($_COOKIE["TeamQR"]);
}
if(!empty($_POST["TeamQR"]))
{
    setcookie("TeamQR", $_POST["TeamQR"], time()+3600*24*30*12, "/", "qrbattle.ru");
    $_COOKIE["TeamQR"]=$_POST["TeamQR"];
}

?><h2>Регистрация на игру</h2><?


if(empty($_COOKIE["TeamQR"]))
{
    echo "<p style='color:red'>Команда еще не выбрана.</p>
    <form method='post' action='' align='center'>
        <select name='TeamQR'>";
        foreach($teams as $k => $t)
            echo "<option value='$k'>$t[name]</option>";
        echo "</select><br><br>
        <button type='submit'>Зарегистрироваться</button>
    </form>";
}
else
{
    echo "Вы зарегистрировались в команде \"".$teams[$_COOKIE["TeamQR"]]['name']."\".<br><br>";
    echo "<form method='post' action=''><input style='font-size: 0.9em;' type='submit' name='reg_down' value='Перерегистрироваться'></form>";

}
?>
<p></p>