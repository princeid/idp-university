<!-- esc_url() improves security -->
<form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
    <!-- lower case "s" is how wordpress identifies a search field -->
    <label for="s" class="headline headline--medium">Perform a new search:</label>
    <div class="search-form-row">
        <input class="s" id="s" type="search" name="s" placeholder="What are you looking for?"> 
        <input class="search-submit" type="submit" value="Search">
    </div>

</form>