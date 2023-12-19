</div>

<script type="text/javascript">
function nofi_list() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            document.getElementById("addlist").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "../nofi/nofi_list.php", true);
    xhttp.send();

}

function loadDoc() {

    setInterval(function() {

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nofi_number").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "../nofi/nofi_rt.php", true);
        xhttp.send();

    }, 1000);

}
loadDoc();

function myAccFunc() {
    var x = document.getElementById("demoAcc");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-green";
    } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
            x.previousElementSibling.className.replace(" w3-green", "");
    }
}
</script>
</body>

</html>