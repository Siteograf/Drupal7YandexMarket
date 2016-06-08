<?php
$term_field = variable_get('yml_export_term_field', '');
$descr_field = variable_get('yml_export_descr_field', '');
$image_field = variable_get('yml_export_image_field', '');
$vendor = variable_get('yml_export_vendor', '');
$sales_notes = variable_get('yml_export_sales_notes', '');

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n" ?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">

<yml_catalog date="<?php echo date('Y-m-d h:i'); ?>">
    <shop>
        <name><?php echo variable_get('site_name', 'Drupal'); ?></name>
        <company><?php echo variable_get('site_name', 'Drupal'); ?></company>
        <url><?php echo $GLOBALS['base_url']; ?></url>

        <currencies>
            <currency id="RUR" rate="1"/>
        </currencies>
        <categories>
            <?php foreach ($categories as $term): ?>
                <category
                    id="<?php echo $term->tid ?>"<?php if ($term->parent): ?> parentId="<?php echo $term->parent ?>"<?php endif; ?>><?php echo ys($term->name) ?></category>
            <?php endforeach; ?>
        </categories>

        <offers>
            <?php
            foreach ($nodes as $node):

                $term = field_get_items('node', $node, $term_field);
                if (isset($term[0])) {
                    $term = $term[0];
                }

                $descr = field_get_items('node', $node, $descr_field);
                if (isset($descr[0])) {
                    $descr = $descr[0];
                }


                $images = field_get_items('node', $node, 'field_images');// $image_field
//                if (isset($images[0])) {
//                     $image = file_create_url($image[0]['uri']);
//                }


                ?>
                <offer id="<?php echo $node->nid ?>" available="true">
                    <url><?php echo url('node/' . $node->nid, array('absolute' => TRUE)); ?></url>
                    <?php /* <vendorCode> echo $node -> model </vendorCode> */ ?>
                    <price><?php echo $node->price['und'][0]['value'] ?></price>

                    <currencyId>RUR</currencyId>
                    <categoryId><?php echo $term['tid']; ?></categoryId>

                    <?php if (!empty($images)): ?>
                        <? foreach ($images as $image) : ?>
                            <picture><?php echo file_create_url($image['uri']);  /*$image['uri']; */ ?></picture>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <delivery><?php echo variable_get('yml_export_delivery', 'true'); ?></delivery>
                    <name><?php echo ys(check_plain($node->title)) ?></name>
                    <model><?php echo ys(check_plain($node->title)) ?></model>
                    <vendor><?= $vendor ?></vendor>
                    <vendorCode>IMD1</vendorCode>
                    <description><?php echo ys(truncate_utf8(strip_tags($descr['value']), 255, TRUE)) ?></description>
                    <sales_notes><?= $sales_notes ?></sales_notes>
                </offer>
            <?php endforeach; ?>
        </offers>

    </shop>
</yml_catalog>
