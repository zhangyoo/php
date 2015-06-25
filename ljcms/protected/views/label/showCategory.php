        <div class="dbcc_con">
            <?php if(!empty($cats)): ?>
            <ul class="dbcc_ul_one">
                <?php foreach ($cats as $cat): ?>
                <li class="dbcc_li_one">
                    <span><?php echo $cat['name']; ?></span>
                    <?php if(!empty($cat['listCate'])): ?>
                    <ul class="dbcc_ul_two">
                        <?php foreach ($cat['listCate'] as $second): ?>
                        <li class="dbcc_li_two">
                            <span><?php echo $second['name']; ?></span>
                            <?php if(!empty($second['listCate'])): ?>
                            <ul class="dbcc_ul_three">
                                <?php foreach ($second['listCate'] as $third): ?>
                                <li class="dbcc_li_three">
                                    <input type="checkbox" name="Cat[]" value="<?php echo $third['id']; ?>" <?php echo in_array($third['id'], $catIds) ? "checked":""; ?>/> <?php echo $third['name']; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <div class="dbcc_save">
            <button rel="<?php echo $lid; ?>" onclick="saveLabelCat(this)">确定( <font><?php echo count($catIds); ?></font> )</button>
        </div>            