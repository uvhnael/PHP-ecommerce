<?php include 'includes/db.php';
include 'includes/header.php'; ?>

<div class="col-6">
    <div class="input-group">
        <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus" data-field="">
            <span class="fas fa-minus"></span> <!-- Use Bootstrap 5's icon classes -->
        </button>
        <input type="number" id="quantity" name="quantity" class="form-control input-number" value="10" min="1" max="100">
        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
            <span class="fas fa-plus"></span> <!-- Use Bootstrap 5's icon classes -->
        </button>
    </div>

    <div class="input-group">
        <button type="button" class="quantity-left-minus btn btn-secondary btn-number" data-type="minus" data-field="">
            <span class="fas fa-minus"></span>
        </button>
        <input type="number" id="quantity" name="quantity" class="form-control input-number" value="1" min="1">
        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="">
            <span class="fas fa-plus"></span>
        </button>
    </div>