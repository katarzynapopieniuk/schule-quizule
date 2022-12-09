<?php
session_start();
//Status zalogowany jeśli widnieje w sesji przenosi odrazu do pliku docelowego
if ((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
    header('Location: ../index.php');
    exit();
}
//Status przekroczonego czasu sesji jeśli widnieje przenosi od razu do pliku docelowego
if ((isset($_SESSION['time_out'])) && ($_SESSION['time_out'] == true)) {
    header('Location: time_out.php');
    exit();
}
$_SESSION['overload'] = true;
$_SESSION['overload'] = "Too many tries you have to wait minute to login again";
echo $_SESSION['overload'];
?>
<!DOCTYPE html>
<html>
<body>

<div id="result"></div>
<!--Odliczanie czasu-->
<script>
    var res = document.getElementById('result'),
        timerArr = [];

    for (var start = 60, i = start; i >= 0; i--) {
        if (i === start) {
            for (var j = 0; j <= start; j++) {
                timerArr[j] = j;
            }
            timerArr = timerArr.reverse();
        }

        (function (idx) {
            setTimeout(function () {
                //console.log(idx);
                res.innerHTML = idx;
                if (idx === 0) {
                    document.write('<a href=login.php>Login</a>');
                }
            }, 1000 * (idx - (idx - timerArr[idx])));
        }(i));

    }

</script>
<?php
$_SESSION['attemps'] = 0;
unset($_SESSION['overload']);
?>
</body>
</html>



