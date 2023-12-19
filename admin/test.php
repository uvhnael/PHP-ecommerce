<form autocomplete="off" action="/action_page.php">
    <div class="autocomplete" style="width: 300px;">
        <input id="myInput" type="text" name="myCountry" placeholder="Enter a country...">
    </div>
    <input type="submit">
</form>
<script>
    var countries = [
        "Afghanistan", "Albania", "Algeria", /* ... */ "Zimbabwe"
    ];
    // Get the input element
    var input = document.getElementById("myInput");

    // Initialize the autocomplete function
    function autocomplete(input, arr) {
        input.addEventListener("input", function(e) {
            var val = this.value;
            closeAllLists();

            if (!val) return;

            // Create a div element for the autocomplete list
            var div = document.createElement("div");
            div.setAttribute("id", this.id + "autocomplete-list");
            div.setAttribute("class", "autocomplete-items");
            this.parentNode.appendChild(div);

            // Filter the array based on user input
            for (var i = 0; i < arr.length; i++) {
                if (arr[i].toUpperCase().includes(val.toUpperCase())) {
                    var item = document.createElement("div");
                    item.innerHTML = arr[i];
                    item.addEventListener("click", function(e) {
                        input.value = this.innerHTML;
                        closeAllLists();
                    });
                    div.appendChild(item);
                }
            }
        });

        // Close the autocomplete list
        function closeAllLists() {
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                x[i].parentNode.removeChild(x[i]);
            }
        }

        // Execute when the user clicks outside the input field
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }

    // Call the autocomplete function with the countries array
    autocomplete(input, countries);
</script>