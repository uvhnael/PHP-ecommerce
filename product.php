<?php
include("includes/db.php");
include("includes/header.php");
include("includes/navbar.php");


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
} else {
    header("Location: index.php");
}

?>
<div class="container section-padding">
    <div class="row">
        <div class="col-md-6">
            <?php
            $sql = "SELECT * FROM galleries WHERE product_id=$id ORDER BY thumbnail DESC";
            $result = mysqli_query($conn, $sql);
            $product_images = mysqli_fetch_all($result, MYSQLI_ASSOC);
            ?>
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($product_images as $key => $product_image) { ?>
                    <button type="button" data-bs-target="#carouselExampleControls"
                        data-bs-slide-to="<?php echo $key; ?>" class="
                        <?php if ($key == 0) {
                            echo "active";
                        } ?>
                        " aria-current="
                        <?php if ($key == 0) {
                            echo "true";
                        } ?>
                        " aria-label="Slide <?php echo $key; ?>"></button>
                    <?php } ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($product_images as $key => $product_image) { ?>
                    <div class="carousel-item <?php if ($key == 0) {
                                                        echo "active";
                                                    } ?>">
                        <img src="<?php echo $product_image['image_path']; ?>" class="d-block w-100"
                            alt="<?php echo $product['product_name']; ?>">
                    </div>
                    <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <h3><?php echo $product['product_name']; ?></h3>
            <p id="price">₫<?php echo $product['regular_price']; ?></p>
            <p><?php echo $product['short_description']; ?></p>
            <form method="post">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="col-md-12">
                    <?php
                    $sql = "SELECT * FROM attributes INNER JOIN product_attributes WHERE product_attributes.attribute_id = attributes.id AND product_attributes.product_id='$id'";
                    $result = mysqli_query($conn, $sql);
                    $attributes = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    $tmp = 1;
                    $attribute_row = mysqli_num_rows($result);
                    foreach ($attributes as $attribute) {
                    ?>
                    <div class="col" id="attribute<?php echo $tmp++; ?>">
                        <label><?php echo $attribute['attribute_name']; ?></label>
                        <?php
                            $sql = "SELECT * FROM attribute_values WHERE attribute_id=" . $attribute['attribute_id'];
                            $result = mysqli_query($conn, $sql);
                            $attribute_values = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            $value = $attribute['id'];
                            foreach ($attribute_values as $attribute_value) {

                                $sql = "SELECT * FROM variant_attribute_values WHERE attribute_value_id=" . $attribute_value['id'];
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_num_rows($result);
                                if ($row == 0) {
                            ?>
                        <button type="button" class="btn btn-outline-secondary m-1"
                            disabled><?php echo $attribute_value['attribute_value']; ?></button>
                        <?php

                                } else {
                                    $vavs_id = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                    $count = 0;
                                    foreach ($vavs_id as $vav_id) {
                                        $sql = "SELECT * FROM variants INNER JOIN variant_values ON variants.id=variant_values.variant_id WHERE variant_attribute_value_id=" . $vav_id['variant_attribute_value_id'];
                                        $result = mysqli_query($conn, $sql);
                                        $variant = mysqli_fetch_assoc($result);
                                        if ($variant['quantity'] == 0) {
                                            $count++;
                                        }
                                    }
                                    if ($count == $row) {
                                    ?>
                        <button type="button" class="btn btn-outline-secondary m-1"
                            disabled><?php echo $attribute_value['attribute_value']; ?></button>
                        <?php

                                    } else {
                                    ?>
                        <button type="button" class="btn btn-outline-secondary m-1" onclick="selected(this)"
                            id="<?php echo $value; ?>"
                            value="<?php echo $attribute_value['id']; ?>"><?php echo $attribute_value['attribute_value']; ?></button>
                        <?php
                                    }
                                }
                            }
                            ?>
                    </div>
                    <?php

                    } ?>
                </div>
                <div class="col-3 pt-3">
                    <div class="input-group">
                        <button type="button" class="quantity-left-minus btn btn-secondary btn-number" data-type="minus"
                            data-field="">
                            <span class="fas fa-minus"></span>
                        </button>
                        <input type="number" id="quantity" name="quantity" class="form-control input-number" value="1"
                            min="1">
                        <button type="button" class="quantity-right-plus btn btn-secondary btn-number" data-type="plus"
                            data-field="">
                            <span class="fas fa-plus"></span>
                        </button>
                    </div>
                    <a id="quantity_left"></a>
                </div>
            </form>
            <div class="col-6 pt-3">
                <div class="d-none alert alert-danger" role="alert" id="alert-section">
                    <p class="text-center">Please select attributes</p>
                </div>
                <div class="row d-flex justify-content-evenly">
                    <div class="col-6"> <button class="btn btn-outline-success" type="submit" name="add_to_cart"
                            onclick="addToCart()">Add to
                            Cart</button>
                    </div>
                    <div class="col-6"> <button class="btn btn-success" type="submit" name="buy_now"
                            onclick="buyNow()">Buy
                            Now</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>



<script>
// array btn_id
var btn_value = [];
var btn_id = [];
var is_fill = [];


function add_data(btn_id, btn_value) {
    // check if btn_id is in array
    if (!this.btn_id.includes(btn_id)) {
        this.btn_id.push(btn_id);

        for (var i = 0; i < this.btn_id.length; i++) {
            if (this.btn_id[i] == btn_id) {
                this.btn_value[i] = btn_value;
            }
        }
    } else {
        for (var i = 0; i < this.btn_id.length; i++) {
            if (this.btn_id[i] == btn_id) {
                if (this.btn_value[i] != btn_value) {
                    this.btn_value[i] = btn_value;
                } else {
                    this.btn_value.splice(i, 1);
                    this.btn_id.splice(i, 1);
                }

            }
        }
    }
}


function selected(btn) {
    var product_id = <?php echo $id; ?>;

    add_data(btn.id, btn.value);



    if (btn.classList.contains("btn-outline-red")) {
        btn.classList.remove("btn-outline-red");
    } else {
        unselected(btn.id);
        btn.classList.add("btn-outline-red");
    }

    if (this.btn_id.length == 0) {
        var btns = document.getElementsByTagName("button");
        for (var i = 0; i < btns.length; i++) {
            if (btns[i].id)
                btns[i].disabled = false;
        }
    } else {


        $.ajax({
            type: "POST",
            url: "get_price.php",
            data: {
                product_id: product_id,
                attribute_value_id: btn_value,

            },
            success: function(data) {
                var data = JSON.parse(data);

                if (data.able_value_id != null) {
                    var btns = document.getElementsByTagName("button");
                    for (var i = 0; i < btns.length; i++) {
                        var myButton = document.getElementById(btns[i].id);
                        if (myButton) {
                            if (data.able_value_id.includes(btns[i].value)) {
                                btns[i].disabled = false;
                            } else {
                                if (btns[i].id != btn_id)
                                    btns[i].disabled = true;
                            }
                        }

                    }

                }
                if (data.quantity) {
                    var alert_section = document.getElementById("alert-section");
                    alert_section.classList.add("d-none");
                    document.getElementById("quantity_left").innerHTML = data.quantity + " available now";
                    document.getElementById("price").innerHTML = "₫" + data.price;
                    // set quantity max
                    document.getElementById("quantity").max = data.quantity;
                    document.getElementById("quantity").value = 1;

                }
            }
        });

    }


}

function unselected(btn_id) {
    // clear all selected
    var btns = document.getElementById(btn_id).parentElement.children;
    for (var i = 0; i < btns.length; i++) {
        btns[i].classList.remove("btn-outline-red");
    }
}

$(document).ready(function() {

    var quantitiy = 0;
    var max_quantity = 1;

    $('.quantity-right-plus').click(function(e) {

        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('#quantity').val());
        var max_quantity = parseInt($('#quantity').attr("max"));
        // If is not undefined

        if (quantity < max_quantity) {
            $('#quantity').val(quantity + 1);
        }


    });

    $('.quantity-left-minus').click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        var quantity = parseInt($('#quantity').val());

        // If is not undefined

        // Increment
        if (quantity > 1) {
            $('#quantity').val(quantity - 1);
        }
    });

    $('#quantity').change(function() {
        var quantity = parseInt($('#quantity').val());
        var max_quantity = parseInt($('#quantity').attr("max"));
        if (quantity > max_quantity) {
            $('#quantity').val(max_quantity);
        }
    });

});

function buyNow() {

    if (this.btn_id.length != <?php echo $attribute_row; ?>) {
        var alert_section = document.getElementById("alert-section");
        alert_section.classList.remove("d-none");
        return;
    }
    // take data from form
    var product_id = $("input[name=product_id]").val();
    var quantity = $("input[name=quantity]").val();

    var btns = document.getElementsByClassName("btn-outline-red");
    var av_id = [];
    for (var i = 0; i < btns.length; i++) {
        av_id.push(btns[i].value);
    }
    // go to checkout
    window.location.href = "add_to_cart.php?product_id=" + product_id + "&quantity=" + quantity + "&av_id=" + av_id;


}

function addToCart() {

    if (this.btn_id.length != <?php echo $attribute_row; ?>) {
        var alert_section = document.getElementById("alert-section");
        alert_section.classList.remove("d-none");
        return;
    }

    // take data from form 
    var product_id = $("input[name=product_id]").val();
    var quantity = $("input[name=quantity]").val();

    var btns = document.getElementsByClassName("btn-outline-red");
    var av_id = [];
    for (var i = 0; i < btns.length; i++) {
        av_id.push(btns[i].value);
    }
    // send data to add_to_cart.php
    $.ajax({

        type: "POST",
        url: "add_to_cart.php",
        data: {
            product_id: product_id,
            quantity: quantity,
            av_id: av_id
        },
        success: function(data) {
            console.log(data);
        }
    });
}
</script>




<?php
include("includes/footer.php");
?>