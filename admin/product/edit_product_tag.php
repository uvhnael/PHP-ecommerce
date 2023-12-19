<?php
session_start();
require_once '../includes/db.php';
if (!isset($_SESSION['email']) & empty($_SESSION['email'])) {
    header('location: login.php');
} else {
    $role = $_SESSION['role'];
    $role_name = $_SESSION['role_name'];
    if ($role < 2) {
        header('location: ../index.php');
    }
}
include '../includes/header.php';
include '../includes/sidebar.php';

if (isset($_GET['product_id']) & !empty($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}

if (isset($_POST) & !empty($_POST)) {
    $tag_name = $_POST['tag_name'];
    $icon = $_POST['icon'];
    $created_by = $_SESSION['id'];

    $sql = "SELECT * FROM tags WHERE tag_name='$tag_name'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        $r = mysqli_fetch_assoc($result);
        $tag_id = $r['id'];
        $sql = "INSERT INTO product_tags (product_id, tag_id) VALUES ('$product_id', '$tag_id')";
        $result = mysqli_query($conn, $sql);
    } else {
        $sql = "INSERT INTO tags (tag_name, icon, created_by) VALUES ('$tag_name', '$icon', '$created_by')";
        $result = mysqli_query($conn, $sql);
        $tag_id = mysqli_insert_id($conn);

        $sql = "INSERT INTO product_tags (product_id, tag_id) VALUES ('$product_id', '$tag_id')";
        $result = mysqli_query($conn, $sql);
    }
    header('location: edit_product_tag.php?product_id=' . $product_id);
}

$sql = "SELECT * FROM tags";
$result = mysqli_query($conn, $sql);
$tags = [];
$icons = [];
while ($r = mysqli_fetch_assoc($result)) {
    array_push($tags, $r['tag_name']);
    array_push($icons, $r['icon']);
}

$sql = "SELECT * FROM tags INNER JOIN product_tags WHERE tags.id = product_tags.tag_id AND product_tags.product_id='$product_id'";
?>
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between">
                <a href="attributes.php?product_id=<?php echo $product_id; ?>" class="btn btn-primary">Back</a>
                <a href="products.php" class="btn btn-primary float-end">Done</a>
            </div>
            <h2>Tags</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tag Name</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, $sql);
                    while ($r = mysqli_fetch_assoc($res)) {
                    ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo $r['tag_name']; ?></td>
                            <td><?php echo $r['icon']; ?></td>
                            <td>
                                <a class="btn btn-danger" href="delete_tag.php?tag_id=<?php echo $r['id']; ?>&product_id=<?php echo $product_id; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>


            <h2>Add Tags</h2>
            <form method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="autocomplete form-group">
                    <label for="tag_name">Tag Name</label>
                    <input type="text" name="tag_name" class="form-control" id="tag_name" placeholder="Tag Name">
                </div>

                <div class="form-group">
                    <label for="icon">Icon</label>
                    <input type="text" name="icon" class="form-control" id="icon" placeholder="Icon">
                </div>

                <div class="d-grid gap-2 col-4 mx-auto pt-4">
                    <button type="submit" class="btn btn-primary ">Add</button>
                </div>
            </form>


        </div>
    </div>
</div>

<script>
    function autocomplete(inp, arr) {

        var currentFocus;
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(a);
            for (i = 0; i < arr.length; i++) {
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    b.addEventListener("click", function(e) {
                        inp.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {

                currentFocus++;
                addActive(x);
            } else if (e.keyCode == 38) {
                currentFocus--;
                addActive(x);
            } else if (e.keyCode == 13) {
                e.preventDefault();
                if (currentFocus > -1) {
                    if (x) x[currentFocus].click();
                }
                var icons = <?php echo json_encode($icons); ?>;
                var tag_name = document.getElementById("tag_name");
                var icon = document.getElementById("icon");
                var index = tags.indexOf(tag_name.value);
                icon.value = icons[index];
            }
        });

        function addActive(x) {
            if (!x) return false;
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }

    var tags = <?php echo json_encode($tags); ?>;

    autocomplete(document.getElementById("tag_name"), tags);
</script>

<?php include '../includes/footer.php'; ?>