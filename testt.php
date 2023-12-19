<?php
include 'includes/db.php';
include 'includes/header.php';



?>
<label>Color</label>
<button type="button" class="btn btn-outline-secondary m-1 " onclick="selected(this)" id="1">White</button>
<button type="button" class="btn btn-outline-secondary m-1" onclick="selected(this)" id="1">Black</button>
<div class="col" id="attribute2">
    <label>Size</label>
    <button type="button" class="btn btn-outline-secondary m-1" onclick="selected(this)" id="2">S</button>
    <button type="button" class="btn btn-outline-secondary m-1" onclick="selected(this)" id="2">M</button>
    <button type="button" class="btn btn-outline-secondary m-1" onclick="selected(this)" id="2">L</button>
</div>


<script>
var btn_id = [];
var btn_text = [];

function add_data(btn_id, btn_text) {
    // check if btn_id is in array
    if (!this.btn_id.includes(btn_id)) {
        this.btn_id.push(btn_id);

        for (var i = 0; i < this.btn_id.length; i++) {
            if (this.btn_id[i] == btn_id) {
                this.btn_text[i] = btn_text;
            }
        }
    } else {
        for (var i = 0; i < this.btn_id.length; i++) {
            if (this.btn_id[i] == btn_id) {
                this.btn_text[i] = btn_text;
            }
        }
    }
}



function selected(btn) {

    add_data(btn.id, btn.textContent);

    $.ajax({
        type: "POST",
        url: "test.php",
        data: {
            product_id: 3,
            attribute_id: btn_id,
            attribute_value: btn_text

        },
        success: function(data) {
            var data = JSON.parse(data);
            for (var i = 0; i < data.attribute_value.length; i++) {
                console.log(data.attribute_id[i]);
                console.log(data.attribute_value[i]);
            }
        }
    });

}
</script>