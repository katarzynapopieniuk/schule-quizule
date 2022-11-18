<?php

class CategoryDisplay {

    public static function display($categories) {
        if(is_array($categories)) {
            foreach($categories as $category): ?>
                <div class="category" id="<?php echo $category?>" onclick="setCategoryPOST('<?php echo $category?>')">
                    <?php echo $category?>
                </div>
            <?php endforeach;
        }
    }
}