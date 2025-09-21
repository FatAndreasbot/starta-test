    <?php
    $db = new SQLite3($_SERVER['DOCUMENT_ROOT']  . '/data/data.db');
    $categories = $db->query('select distinct category from products');
    $prices = $db->query('select min(price) as min, max(price) as max from products')->fetchArray(SQLITE3_ASSOC);
    ?>

    <body>
        <form id="filters">
            <div>
                <select name="category">
                    <option value="all">all</option>
                    <?php while ($row = $categories->fetchArray(SQLITE3_ASSOC)) {
                        $option = '<option value="' . $row["category"] . '">' . $row["category"] . "</option>";
                        echo $option;
                    } ?>
                </select>
            </div>
            <div>
                <label><?php echo $prices['min'] ?></label>
                <input name="price-range" id="price-range" type="range" min="<?php echo $prices['min'] ?>" value="<?php echo $prices['max'] ?>" max="<?php echo $prices['max'] ?>">
                <label><?php echo $prices['max'] ?></label><br>
                <label id="current-maximum"></label>
            </div>
            <div>
                <input id="in-stock" name="in-stock" type="checkbox"><label for="in-stock">in stock</label>
            </div>
            <div>
                <select name="order">
                    <option style="display:none" disabled selected value>-- select on option --</option>
                    <option value="date">date</option>
                    <option value="price">price</option>
                    <option value="rating">rating</option>
                </select>
            </div>
        </form>
    </body>

    <script type="text/javascript" src="/js/index_catalog.js"></script>